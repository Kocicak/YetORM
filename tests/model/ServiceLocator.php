<?php


class ServiceLocator
{

	/** @var Nette\Caching\Storages\FileStorage */
	protected static $cacheStorage = NULL;

	/** @var Nette\Database\Connection */
	protected static $connection = NULL;

	/** @var BookRepository */
	protected static $bookRepository = NULL;

	/** @var AuthorRepository */
	protected static $authorRepository = NULL;

	/** @var BookFacade */
	protected static $bookFacade = NULL;



	/** @return Nette\Caching\Storages\FileStorage */
	static function getCacheStorage()
	{
		if (static::$cacheStorage === NULL) {
			static::$cacheStorage = new Nette\Caching\Storages\FileStorage(__DIR__ . '/../temp');
		}

		return static::$cacheStorage;
	}



	/** @return Nette\Database\Connection */
	static function getConnection()
	{
		if (static::$connection === NULL) {
			static::$connection = new Nette\Database\Connection('mysql:host=localhost;dbname=yetorm_test', 'root', '');
			static::$connection->setCacheStorage(static::getCacheStorage());
			Nette\Database\Helpers::loadFromFile(static::$connection, __DIR__ . '/db/db.sql');
		}

		return static::$connection;
	}



	/** @return BookRepository */
	static function getBookRepository()
	{
		if (static::$bookRepository === NULL) {
			static::$bookRepository = new BookRepository(static::getConnection());
		}

		return static::$bookRepository;
	}



	/** @return AuthorRepository */
	static function getAuthorRepository()
	{
		if (static::$authorRepository === NULL) {
			static::$authorRepository = new AuthorRepository(static::getConnection());
		}

		return static::$authorRepository;
	}



	/** @return BookFacade */
	static function getBookFacade()
	{
		if (static::$bookFacade === NULL) {
			static::$bookFacade = new BookFacade(static::getBookRepository());
		}

		return static::$bookFacade;
	}

}
