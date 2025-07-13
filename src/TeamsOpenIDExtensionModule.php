<?php
/*
 * SimpleID
 *
 * Copyright (C) Kelvin Mo 2014
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this program; if not, write to the Free
 * Software Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 */

namespace SimpleID\Modules\Protocols\OpenID\Teams;

use SimpleID\Module;
use SimpleID\Auth\AuthManager;
use SimpleID\Protocols\OpenID\OpenIDResponseBuildEvent;
use SimpleID\Util\Events\BaseDataCollectionEvent;

/**
 * Implements the Launchpad OpenID Teams extension.
 *
 *
 * @package simpleid
 * @subpackage extensions
 * @link https://dev.launchpad.net/OpenIDTeams
 * @filesource
 */
class TeamsOpenIDExtensionModule extends Module {
    /** Namespace for the Teams extension */
    const OPENID_NS_TEAMS = 'http://ns.launchpad.net/2007/openid-teams';

    /**
     * Returns the support for teams in SimpleID XRDS document
     *
     * @param BaseDataCollectionEvent $event
     * @return void
     */
    function onXrdsTypes(BaseDataCollectionEvent $event) {
        $event->addResult(self::OPENID_NS_TEAMS);
    }

    /**
     * @see SimpleID\Protocols\OpenID\OpenIDResponseBuildEvent
     * @return void
     */
    public function onOpenIDResponseBuildEvent(OpenIDResponseBuildEvent $event) {
        // We only deal with positive assertions
        if (!$event->isPositiveAssertion()) return;

        // We only respond if the extension is requested
        /** @var \SimpleID\Protocols\OpenID\Request */
        $request = $event->getRequest();
        /** @var \SimpleID\Protocols\OpenID\Response */
        $response = $event->getResponse();
        
        if (!$request->hasExtension(self::OPENID_NS_TEAMS)) return;

        $auth = AuthManager::instance();
        $user = $auth->getUser();

        $teams_request = $request->getParamsForExtension(self::OPENID_NS_TEAMS);
        if (!isset($teams_request['query_membership'])) return;

        $alias = $response->getAliasForExtension(self::OPENID_NS_TEAMS, 'teams');
        $response['ns.' . $alias] = self::OPENID_NS_TEAMS;

        $query_membership = (isset($teams_request['query_membership'])) ? explode(',', $teams_request['query_membership']) : [];
        $is_member = [];

        $user_teams = ($user->pathExists('teams.teams')) ? $user->pathGet('teams.teams') : [];

        foreach ($query_membership as $team) {
            if (in_array($team, $user_teams)) $is_member[] = $team;
        }

        $response[$alias . '.is_member'] = implode(',', $is_member);
    }


}

?>
