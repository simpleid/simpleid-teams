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

/**
 * Implements the Launchpad OpenID Teams extension.
 * 
 *
 * @package simpleid
 * @subpackage extensions
 * @link https://dev.launchpad.net/OpenIDTeams
 * @filesource
 */

/** Namespace for the teams extension */
define('OPENID_NS_TEAMS', 'http://ns.launchpad.net/2007/openid-teams');


/**
 * Returns the support for OpenID Teams in SimpleID XRDS document
 *
 * @return array
 * @see hook_xrds_types()
 */
function teams_xrds_types() {
    return array(OPENID_NS_TEAMS);
}

/**
 * @see hook_response()
 */
function teams_response($assertion, $request) {
    global $user;
    global $version;
    
    // We only deal with positive assertions
    if (!$assertion) return array();
    
    // We only respond if the extension is requested
    if (!openid_extension_requested(OPENID_NS_TEAMS, $request)) return array();
    
    $request = openid_extension_filter_request(OPENID_NS_TEAMS, $request);
    if (!isset($request['query_membership'])) return array();

    $response = array();
    $alias = openid_extension_alias(OPENID_NS_TEAMS);
    $response['openid.ns.' . $alias] = OPENID_NS_TEAMS;


    $mode = $request['mode'];
    
    $query_membership = (isset($request['query_membership'])) ? explode(',', $request['query_membership']) : array();
    $is_member = array();

    $user_teams = (isset($user['teams']['teams'])) ? explode(',', $user['teams']['teams']) : array();

    foreach ($query_membership as $team) {
        if (in_array($team, $user_teams)) $is_member[] = $team;
    }

    $response['openid.' . $alias . '.is_member'] = implode(',', $is_member);
    
    return $response;
}

/**
 * Returns an array of fields that need signing.
 *
 * @see hook_signed_fields()
 */
function teams_signed_fields($response) {
    // We only respond if the extension is requested
    if (!openid_extension_requested(OPENID_NS_TEAMS, $response)) return array();
    
    $fields = array_keys(openid_extension_filter_request(OPENID_NS_TEAMS, $response));
    $alias = openid_extension_alias(OPENID_NS_TEAMS);
    $signed_fields = array();

    if (isset($response['openid.ns.' . $alias])) $signed_fields[] = 'ns.' . $alias;
    foreach ($fields as $field) {
        if (isset($response['openid.' . $alias . '.' . $field])) $signed_fields[] = $alias . '.' . $field;
    }
    
    return $signed_fields;
}

?>
