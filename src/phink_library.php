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
 * but WITHOUT ANY WARRANTY, without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class PhinkLibrary {

    public static function mount() {

        $filenames = [ 
            'log/log.php',
            'core/static_object.php',
            'core/object.php',
            'core/application.php',
            'web/http_transport_interface.php',
            'web/http_transport.php',
            'web/web_object_interface.php',
            'web/web_object.php',
            'web/web_application.php',
            'web/static_application.php',
            'core/router.php',
            'web/curl.php',
            'web/response.php',
            'web/ui/custom_control.php',
            'web/ui/control.php',
            'mvc/custom_controller.php',
            'mvc/controller.php',
            'data/crud_queries.php',
            'mvc/model.php',
            'web/ui/code_generator.php',
            'mvc/custom_view.php',
            'mvc/view.php',
            'web/web_router.php',
            'rest/rest_router.php',
            'rest/rest_controller.php',
            'web/ui/html_control.php',
            'core/registry.php',
            'web/request.php',
            'utils/file_utils.php',
            'auth/authentication.php',
            'data/data_access.php',
            'data/server_type.php',
            'configuration/configurable.php',
            'configuration/data/data_configuration.php',
            'data/client/pdo/pdo_configuration.php',
            'data/connection.php',
            'data/client/pdo/pdo_connection.php',
            'data/custom_command.php',
            'data/client/pdo/pdo_command.php',
            'data/data_statement.php',
            'data/client/pdo/pdo_data_statement.php',
            'utils/zip.php',
            'utils/string_utils.php',
            'collections/array_list.php',
            'text/regex_match.php',
            'text/regex.php',
            'xml/xml_match.php',
            'xml/xml_document.php',
            'xml/xml_utils.php',
            'mvc/partial_controller.php',
            'mvc/partial_view.php',
            'web/ui/html_element.php',
            'web/ui/html_pattern.php',
            //'web/ui/grid.class.php',
            //'web/ui/pager.class.php',
            'data/ui/data_tag.php',
            //'data/ui/data_grid.class.php',
            'data/ui/data_binder.php',
            'web/ui/plugin/custom_plugin.php',
            'web/ui/plugin/table.php',
            'web/ui/plugin/ulli.php',
            'web/ui/plugin/olli.php',
            'web/ui/plugin/accordion.php',
            'web/ui/plugin_renderer.php',
            'web/ui/widget/plugin/plugin.class.php',
            'web/ui/widget/plugin/plugin_child.php'
        ];

//        $fw_content = '';
        foreach ($filenames as $filename) {
            $filename = 'phink' . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR,  $filename);
            include  __DIR__ . DIRECTORY_SEPARATOR . $filename;
//            $fw_content .= file_get_contents($filename, FILE_USE_INCLUDE_PATH);
        }
        
//        $fw_content = '<?php' . str_replace('<?php', '', $fw_content);
        
//        file_put_contents('phink.inc', $fw_content);
        
        
        
    }
}

PhinkLibrary::mount();