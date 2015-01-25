About this version
------------------

This is a community driven fork of symfony 1, as official support [ended in November 2012](http://symfony.com/blog/symfony-1-4-end-of-maintenance-what-does-it-mean). (Also note: new tickets can no longer be opened on the original Symfony 1.4 site.)

**This fork is solely intended to keep legacy Symfony 1.4 applications running securely** on currently-maintained versions of PHP, **without changes to your application code** unless security absolutely requires them. PHP 5.3.4 is a minimum requirement. We actively test with PHP 5.4.x and are incorporating community fixes for 5.5.x and above.

**Please do not use this fork for new projects. You should move on.** If you like PHP, check out [Symfony2](http://symfony.com/).

Acknowledgements
----------------

We are using the [drak fork of Doctrine 1.2](https://github.com/drak/doctrine1), and we have cherry-picked backwards-compatible PHP compatibility fixes from the [L'Express fork of Symfony 1.4](https://github.com/lexpress/symfony1). That is a fine fork but its goals include new backwards-incompatible features, thus this separate fork for maintenance work only.

LEGACY README FOLLOWS

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

Requirements
------------

PHP 5.3.4 and up. See prerequisites on http://symfony.com/legacy/doc/getting-started/1_4/en/02-Prerequisites

Installation
------------

See http://symfony.com/legacy/doc/getting-started/1_4/en/03-Symfony-Installation

Option 1: Using Git submodules (if your project is in git):

    git init # your project
    git submodule add https://github.com/punkave/symfony1 lib/vendor/symfony
    git submodule update --init --recursive

Option 2: Using svn externals (if your project is still in svn):

    svn propedit svn:externals lib/vendor

    [paste in as one line:]

    symfony https://github.com/punkave/symfony1

Documentation
-------------

Read the official [symfony1 documentation](http://symfony.com/legacy)

Contributing
------------

You can send pull requests or create an issue.
