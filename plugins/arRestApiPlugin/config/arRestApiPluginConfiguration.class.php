<?php

/*
 * This file is part of the Access to Memory (AtoM) software.
 *
 * Access to Memory (AtoM) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Access to Memory (AtoM) is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Access to Memory (AtoM).  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * arRestApiPluginConfiguration configuration.
 *
 * @package     arRestApiPluginConfiguration
 * @subpackage  config
 */
class arRestApiPluginConfiguration extends sfPluginConfiguration
{
  public function routingLoadConfiguration(sfEvent $event)
  {
    $routing = $event->getSubject();

    // How slow is inserting the routes here? I don't think I can obtain the
    // same results using a nested routing.yml files in arRestApiPlugin because
    // there's no way to bypass some of the catch-any routes in the main yaml.
    // This is probably not being cached at all :(

    $routing->insertRouteBefore(
      'slug;default_index',
      'api_dashboard_index',
      new sfRequestRoute(
        '/api/dashboard',
        array('module' => 'api', 'action' => 'dashboardIndex'),
        array('requirements' => array('GET'))));

    $routing->insertRouteBefore(
      'slug;default_index',
      'api_aips_index',
      new sfRequestRoute(
        '/api/aips',
        array('module' => 'api', 'action' => 'aipsIndex'),
        array('requirements' => array('GET'))));

  }

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    // Enable sfInstallPlugin module
    $enabledModules = sfConfig::get('sf_enabled_modules');
    $enabledModules[] = 'api';
    sfConfig::set('sf_enabled_modules', $enabledModules);

    // Connect event listener to add routes
    $this->dispatcher->connect('routing.load_configuration', array($this, 'routingLoadConfiguration'));
  }
}