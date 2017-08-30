<?php
/**
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @license
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @link http://phpwhois.pw
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 */

if (!defined('__NAMEINTEL_HANDLER__')) {
    define('__NAMEINTEL_HANDLER__', 1);
}

require_once 'whois.parser.php';

class nameintel_handler
{
    public function parse($data_str, $query)
    {
        $items = array(
            'owner' => 'Registrant Contact:',
            'admin' => 'Administrative Contact:',
            'tech' => 'Technical Contact',
            'domain.name' => 'Domain Name:',
            'domain.status' => 'Status:',
            'domain.nserver' => 'Name Server:',
            'domain.created' => 'Creation Date:',
            'domain.expires' => 'Expiration Date:'
        );

        $r = easy_parser($data_str, $items, 'dmy', array(), false, true);

        if (isset($r['domain']['sponsor']) && is_array($r['domain']['sponsor'])) {
            $r['domain']['sponsor'] = $r['domain']['sponsor'][0];
        }

        foreach ($r as $key => $part) {
            if (isset($part['address'])) {
                $r[$key]['organization'] = array_shift($r[$key]['address']);
                $r[$key]['address']['country'] = array_pop($r[$key]['address']);
            }
        }
        return $r;
    }
}
