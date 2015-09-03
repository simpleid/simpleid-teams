SimpleID OpenID Teams Module
============================

This SimpleID module implements the [OpenID Teams](https://dev.launchpad.net/OpenIDTeams)
extension developed by Launchpad.  It allows you to specify a series of strings (known as teams)
which SimpleID will return to web sites which request them.

Installing and Uninstalling
---------------------------

This module can be installed by running the following [Composer](https://getcomposer.org/)
command in the SimpleID web directory:

```bash
composer require simpleid/simpleid-teams
```

See the [SimpleID documentation](http://simpleid.koinic.net/docs/2/modules/)
for further instructions.

Configuration
-------------

The teams you belong to are specified in your [user file](http://simpleid.koinic.net/docs/2/users/), under the value `teams.teams`
as an array.  For example:

```yaml
teams:
    teams:
        - foo
        - bar
```


Security considerations
-----------------------

Team information is provided to every OpenID relying party that requests it without verification.
Therefore you should regarding your team membership as public information and should **not** put any
security-sensitive information.  In particular, be careful about team names (such as "administrator")
which may indicate that you have privileged access to resources.


Bugs
----

To report bugs, please use the [SimpleID trac](http://simpleid.koinic.net/trac).


Licensing
---------

Licensing information for the core SimpleID software can be found in the
[source distribution](http://simpleid.koinic.net/download).
