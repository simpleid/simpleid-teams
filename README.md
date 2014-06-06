SimpleID OpenID Teams Extension
===============================

This SimpleID extension implements the [OpenID Teams](https://dev.launchpad.net/OpenIDTeams)
extension developed by Launchpad.  It allows you to specify a series of strings (known as teams)
which SimpleID will return to web sites which request them.

Installing and Uninstalling
---------------------------

See the [SimpleID documentation](http://simpleid.koinic.net/documentation/using-simpleid/extensions/installing-and-uninstalling-extensions)
for instructions

Configuration
-------------

The teams you belong to specified in your [identity file](http://simpleid.koinic.net/documentation/getting-started/setting-identity/identity-files).
Add a section called `teams` in your identity file and specify the teams under the key `teams` within
that section.  Separate multiple teams with commas (without spaces).

An example is given below.

<pre>
[teams]
teams="foo,bar"
</pre>


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

Licensing information for the OpenShift distribution can be found in the file COPYING.txt.

Licensing information for the core SimpleID software can be found in the
[source distribution](http://simpleid.koinic.net/download).
