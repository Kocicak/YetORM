<?php


class BookRepository extends YetORM\Repository
{

	/** @return Book */
	function findById($id)
	{
		return new Book($this->getTable()->get($id));
	}



	/** @return YetORM\EntityCollection */
	function findByTag($name)
	{
		return $this->createCollection($this->getTable()->where('book_tag:tag.name', $name));
	}



	/** @return YetORM\EntityCollection */
	function findAll()
	{
		return $this->createCollection($this->getTable());
	}

}
