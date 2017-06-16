<?php
/*
 * Copyright (C) 2016 David Blanchard
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
include 'phink/globals.php';
include 'phink/core/static_object.php';
include 'phink/core/object.php';
include 'phink/web/web_object.php';
include 'phink/web/ui/custom_control.php';
include 'phink/web/ui/html_control.php';
include 'phink/core/registry.php';
include 'phink/web/request.php';
include 'phink/utils/file_utils.php';
include 'phink/auth/authentication.php';
include 'phink/data/data_access.php';
include 'phink/data/server_type.php';
include 'phink/configuration/configurable.php';
include 'phink/configuration/data/data_configuration.php';
include 'phink/data/client/pdo/pdo_configuration.php';
include 'phink/data/connection.php';
include 'phink/data/client/pdo/pdo_connection.php';
include 'phink/data/crud_queries.php';
include 'phink/data/custom_command.php';
include 'phink/data/client/pdo/pdo_command.php';
include 'phink/data/data_statement.php';
include 'phink/data/client/pdo/pdo_data_statement.php';
include 'phink/web/response.php';
include 'phink/mvc/custom_controller.php';
include 'phink/mvc/controller.php';
include 'phink/mvc/model.php';
include 'phink/web/ui/code_generator.php';
include 'phink/mvc/custom_view.php';
include 'phink/mvc/view.php';
include 'phink/utils/string_utils.php';
include 'phink/collections/array_list.php';
include 'phink/text/regex_match.php';
include 'phink/text/regex.php';
include 'phink/xml/xml_match.php';
include 'phink/xml/xml_document.php';
include 'phink/xml/xml_utils.php';
include 'phink/mvc/partial_controller.php';
include 'phink/mvc/partial_view.php';
include 'phink/rest/http_transport.php';
include 'phink/core/custom_router.php';
include 'phink/rest/rest_router.php';
include 'phink/rest/rest_controller.php';
include 'phink/web/ui/control.php';
include 'phink/web/ui/html_element.php';
include 'phink/web/ui/html_pattern.php';
//include 'phink/web/ui/grid.class.php';
//include 'phink/web/ui/pager.class.php';
include 'phink/data/ui/data_tag.php';
//include 'phink/data/ui/data_grid.class.php';
include 'phink/data/ui/data_binder.php';
include 'phink/web/ui/plugin/custom_plugin.php';
include 'phink/web/ui/plugin/table.php';
include 'phink/web/ui/plugin/ulli.php';
include 'phink/web/ui/plugin/olli.php';
include 'phink/web/ui/plugin/accordion.php';
include 'phink/web/ui/plugin_renderer.php';
include 'phink/web/ui/widget/plugin/plugin.class.php';
include 'phink/web/ui/widget/plugin/plugin_child.php';
