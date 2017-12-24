Hello there, wanderer.
Before you read or do anything, keep in mind, **this codebase was built in 2007**.

Don't expect anything fancy.

For a very long time, it was run from a crappy VPS somewhere. Which went from $3 / month to $12 / month. A little too expensive for a unmaintained website.

So I'm migrating everything to Herokuish, and deploying to a dokku server I already own.

I'm not proud of this source code. You probably shouldn't read it.
The only reason it's open source it I wanted a fast circleCI.

## How to?

For future me, if you need to move to another server:
* Create a SQL DB
* Import a database dump (I have one on my Dropbox)
* Set up the volumes
* Push to Heroku / dokku / whatever
