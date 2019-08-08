About this version
------------------

This is a community driven fork of symfony 1, as official support [ended in November 2012](http://symfony.com/blog/symfony-1-4-end-of-maintenance-what-does-it-mean). (Also note: new tickets can no longer be opened on the original Symfony 1.4 site.)

**This fork is mainly intended to keep legacy Symfony 1.4 applications running securely** on currently-maintained versions of PHP, **without changes to your application code** unless security absolutely requires them. PHP 7 is the version it is built and tested on.  


Acknowledgements
----------------

We are using the [drak fork of Doctrine 1.2](https://github.com/drak/doctrine1), and we have cherry-picked backwards-compatible PHP compatibility fixes from the [L'Express fork of Symfony 1.4](https://github.com/lexpress/symfony1). That is a fine fork but its goals include new backwards-incompatible features, thus this separate fork for maintenance work only.

Installation
------------

That's the tricky bit. We don't want to break your legacy svn workflow, because it's not broken. We would like to use github's support for svn, but it has [two](https://github.com/isaacs/github/issues/344) [bugs](https://github.com/isaacs/github/issues/345) that get in the way of using svn externals to solve the problem of installing symfony and doctrine.

Our preferred workaround can be found in these scripts:

[Install Symfony and Doctrine](http://svn.apostrophenow.org/sandboxes/asandbox/branches/1.5/install-symfony)

[Update Your Project, Symfony and Doctrine](http://svn.apostrophenow.org/sandboxes/asandbox/branches/1.5/update)

The first script is a one-time installation tool. The second script does an `svn up` of your project, then `git pull` for both Symfony and Doctrine.

Make sure you remove your existing `lib/vendor/symfony` folder, remove any `svn:externals` setting that is refreshing it, and `svn:ignore` it before switching over to this approach.

**"What about composer?"** We are open to a pull request for composer support, as long as it does not break things for those of us who are not using it.

LEGACY README FOLLOWS
---------------------

About symfony
-------------

Symfony is a complete framework designed to optimize the development of web applications by way of several key features.
For starters, it separates a web application's business rules, server logic, and presentation views.
It contains numerous tools and classes aimed at shortening the development time of a complex web application.
Additionally, it automates common tasks so that the developer can focus entirely on the specifics of an application.
The end result of these advantages means there is no need to reinvent the wheel every time a new web application is built!

Symfony was written entirely in PHP 5.
It has been thoroughly tested in various real-world projects, and is actually in use for high-demand e-business websites.
It is compatible with most of the available databases engines, including MySQL, PostgreSQL, Oracle, and Microsoft SQL Server.
It runs on *nix and Windows platforms.

Documentation
-------------

Read the official [symfony1 documentation](http://symfony.com/legacy)

Contributing
------------

You can send pull requests or create an issue. At this late date, only security bugs, warnings and errors from newer versions of PHP, and new bugs introduced by commits in this fork are of interest.

