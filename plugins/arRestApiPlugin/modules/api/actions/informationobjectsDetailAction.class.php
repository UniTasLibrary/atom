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

class ApiInformationObjectsDetailAction extends QubitApiAction
{
  protected function get($request)
  {
    try
    {
      $result = QubitSearch::getInstance()->index->getType('QubitInformationObject')->getDocument($this->request->id);
    }
    catch (\Elastica\Exception\NotFoundException $e)
    {
      throw new QubitApi404Exception('Information object not found');
    }

    $doc = $result->getData();
    $data = array();

    $data['id'] = $result->getId();
    $data['title'] = get_search_i18n($doc, 'title');
    $data['level_of_description_id'] = (int)$doc['levelOfDescriptionId'];

    $data['dc'] = array();
    $this->addItemToArray($data['dc'], 'identifier', $doc['identifier']);
    $this->addItemToArray($data['dc'], 'title', get_search_i18n($doc, 'title'));
    $this->addItemToArray($data['dc'], 'description', get_search_i18n($doc, 'scopeAndContent'));
    $this->addItemToArray($data['dc'], 'format', 'This is the format');
    $this->addItemToArray($data['dc'], 'source', 'This is the source');
    $this->addItemToArray($data['dc'], 'rights', 'These are the rights');

    return $data;
  }

  protected function put($request, $payload)
  {
    $io = $this->fetchInformationObjectOr404();

    // TODO: restrict to allowed fields
    foreach ($payload as $field => $value)
    {
      $field = lcfirst(sfInflector::camelize($field));
      $io->$field = $value;
    }

    $io->save();

    return $this->get($request);
  }

  protected function delete()
  {
    $io = $this->fetchInformationObjectOr404();

    return array('status' => 'deleted');
  }

  protected function fetchInformationObjectOr404()
  {
    if (QubitInformationObject::ROOT_ID === (int)$this->request->id)
    {
      throw new QubitApi404Exception('Information object not found');
    }

    if (null === $io = QubitInformationObject::getById($this->request->id))
    {
      throw new QubitApi404Exception('Information object not found');
    }

    return $io;
  }
}
