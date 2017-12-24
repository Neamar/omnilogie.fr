Hello there, wanderer.
Before you read or do anything, keep in mind, **this codebase was built in 2007**.

Don't expect anything fancy.

For a very long time, it was run from a crappy VPS somewhere. Which went from $3 / month to $12 / month. A little too expensive for a unmaintained website.

So I'm migrating everything to Herokuish, and deploying to a dokku server I already own.

I'm not proud of this source code. You probably shouldn't read it.
The only reason it's open source it I wanted a fast circleCI.

## How to?

For future me, if you need to move to another server:

### Setup SQL
Create a DB somewhere.
Add the connexion identifier (`mysql://...`) as `DATABASE_URL`.

Import a database dump (there is one in my Dropbox, `/svg/DB/neamar.fr/`). Only import the `OMNI_` tables.

### Setup the volumes
`images/O`, `images/Banner` and `images/GD` are not stored in Git, since they're dynamic.

With dokku, you can just run

```
mkdir /var/lib/dokku/data/storage/omnilogie
mkdir /var/lib/dokku/data/storage/omnilogie/images
# cp the three folders from a dump,
chown -R 32767:32767 /var/lib/dokku/data/storage/omnilogie
dokku storage:mount omnilogie /var/lib/dokku/data/storage/omnilogie/images/O:/app/images/O
dokku storage:mount omnilogie /var/lib/dokku/data/storage/omnilogie/images/Banner:/app/images/Banner
dokku storage:mount omnilogie /var/lib/dokku/data/storage/omnilogie/images/GD:/app/images/GD


```
* Set up the volumes
* Push to Heroku / dokku / whatever
