<?php
/*
 * Minacl Project: An HTML forms library for PHP
 *          https://github.com/mellowplace/PHP-HTML-Driven-Forms/
 * Copyright (c) 2010, 2011 Rob Graham
 *
 * This file is part of Minacl.
 *
 * Minacl is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 *
 * Minacl is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with Minacl.  If not, see
 * <http://www.gnu.org/licenses/>.
 */

/**
 * Base form class that all Symfony generated Minacl forms will extend
 *
 * @author Rob Graham <htmlforms@mellowplace.com>
 * @package sfMinaclPlugin
 */
abstract class sfFormMinacl extends phForm
{
	/**
	 * The model class this form is for
	 *
	 * @var object
	 */
	protected $object = null;

	public function __construct($name, $template, $object = null)
	{

		$class = $this->getModelName();
		if (!$object)
		{
			$this->object = new $class();
		}
		else
		{
			if (!$object instanceof $class)
			{
				throw new sfException(sprintf('The "%s" form only accepts a "%s" object.', get_class($this), $class));
			}

			$this->object = $object;
		}
		
		parent::__construct($name, $template);
	}

	/**
	 * returns the class of the model that this form is dealing with
	 * @return string
	 */
	public abstract function getModelName();

	/**
	 * Gets the object that this form deals with
	 */
	public function getObject()
	{
		return $this->object;
	}
	
	/**
	 * @return boolean if there is file uploads on this form then it is a multipart form
	 */
	public function isMultipart()
	{
		/*
		 * go through all our subforms, if one of them is a multipart
		 * then that makes us a multipart
		 */
		$subForms = $this->_forms;
		foreach($subForms as $f)
		{
			if($f instanceof sfFormMinacl && $f->isMultipart())
			{
				return true;
			}
		}
		/*
		 * got through all data items, if one of them is file data then we
		 * are multipart
		 */
		$dataItems = $this->_view->getAllData();
		foreach($dataItems as $i)
		{
			if($i instanceof phFileFormDataItem)
			{
				return true;
			}
		}
		
		return false;
	}
}