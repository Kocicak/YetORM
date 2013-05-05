<?php


class BookRepository extends YetORM\Repository
{

	/**
	 * @param  string
	 * @param  int
	 * @param  string
	 * @param  bool
	 * @param  array
	 * @return Book
	 */
	function create($title, $author, $written, $available = TRUE, array $tags = array())
	{
		$this->begin();

			$row = $this->getTable()->insert(array(
				'author_id' => $author,
				'book_title' => $title,
				'written' => $written,
				'available' => $available,
			));

			$tagMap = $this->getTable('tag')->fetchPairs('name', 'id');
			foreach ($tags as $name) {
				$this->getTable('book_tag')->insert(array(
					'book_id' => $row->id,
					'tag_id' => $tagMap[$name],
				));
			}

		$this->commit();

		return new Book($row);
	}



	/**
	 * @param  Book
	 * @return int
	 */
	function persist(Book $book)
	{
		$this->begin();

			$row = $book->toRow();
			if ($row->getNative() === NULL) {
				$inserted = $this->getTable()->insert($row->getModified());
				$refreshed = $this->getTable()->select('*')->get($inserted->getPrimary());

				$book->refresh($refreshed);
				$rows = 1;

			} else {
				$rows = $book->toRow()->update();
			}

		$this->commit();

		return $rows;
	}



	/**
	 * @param  Book
	 * @return int
	 */
	function delete(Book $book)
	{
		$this->begin();
			$rows = $book->toRow()->getNative()->delete();
		$this->commit();

		return $rows;
	}



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
