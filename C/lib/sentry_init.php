<?php
// Sentry bootstrap. Loaded once from index.php right after vendor/autoload.php.
// No-op when SENTRY_DSN is unset (keeps local dev silent).

$sentryDsn = getenv('SENTRY_DSN');
if (empty($sentryDsn)) {
    return;
}

\Sentry\init([
    'dsn' => $sentryDsn,
    'traces_sample_rate' => 0.2,
    'environment' => getenv('DEBUG') == 1 ? 'dev' : 'prod',
    'send_default_pii' => true,
    'before_send' => function (\Sentry\Event $event): \Sentry\Event {
        $request = $event->getRequest();
        if (!empty($request)) {
            // Mirrors the scrubbing in C/lib/debug.php::getDebugLog().
            if (isset($request['cookies']) && is_array($request['cookies'])) {
                foreach ($request['cookies'] as $k => $v) {
                    if (preg_match('/(pass|token|secret|auth)/i', $k)) {
                        $request['cookies'][$k] = '[Filtered]';
                    }
                }
            }
            if (isset($request['data']) && is_array($request['data'])) {
                foreach ($request['data'] as $k => $v) {
                    if (preg_match('/(pass|token|secret|auth)/i', $k)) {
                        $request['data'][$k] = '[Filtered]';
                    }
                }
            }
            $event->setRequest($request);
        }

        $extra = $event->getExtra();
        if (isset($extra['session']['Membre']['Articles'])) {
            unset($extra['session']['Membre']['Articles']);
            $event->setExtra($extra);
        }

        return $event;
    },
]);

// Call this from index.php after session_start() — at sentry_init.php load time
// $_SESSION is not yet populated.
function sentry_attach_session_scope(): void
{
    \Sentry\configureScope(function (\Sentry\State\Scope $scope): void {
        if (!empty($_SESSION)) {
            $session = $_SESSION;
            if (isset($session['Membre']['Articles'])) {
                unset($session['Membre']['Articles']);
            }
            $scope->setExtra('session', $session);
        }
        if (!empty($_SESSION['Membre']['Pseudo'])) {
            $scope->setUser(['username' => $_SESSION['Membre']['Pseudo']]);
        }
    });
}
