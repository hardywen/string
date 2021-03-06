<?php

use BigElephant\String\String;
use Mockery as m;

class StringTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->pluralizer = m::mock('BigElephant\String\Pluralizer\PluralizerInterface');
		$this->string = new String($this->pluralizer);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testStringLengthIsCorrect()
	{
		$this->assertEquals(6, $this->string->length('Taylor'));
		$this->assertEquals(5, $this->string->length('ラドクリフ'));
	}

	public function testStringCanBeConvertedToLowercase()
	{
		$this->assertEquals('taylor', $this->string->lower('TAYLOR'));
		$this->assertEquals('άχιστη', $this->string->lower('ΆΧΙΣΤΗ'));
	}

	public function testStringCanBeConvertedToUppercase()
	{
		$this->assertEquals('TAYLOR', $this->string->upper('taylor'));
		$this->assertEquals('ΆΧΙΣΤΗ', $this->string->upper('άχιστη'));
	}

	public function testStringCanBeConvertedToUpperWords()
	{
		$this->assertEquals('Taylor', $this->string->upperWords('taylor'));
		$this->assertEquals('Taylor Better Tester', $this->string->upperWords('taylor better tester'));
		$this->assertEquals('TAYLOR BeTteR TEsTer', $this->string->upperWords('TAYLOR beTteR tEsTer'));
		//$this->assertEquals('Άχιστη', $this->string->upperWords('άχιστη'));
	}

	public function testStringCanBeConvertedToTitleCase()
	{
		$this->assertEquals('Taylor', $this->string->titleCase('taylor'));
		$this->assertEquals('Taylor Better Tester', $this->string->titleCase('taylor better tester'));
		$this->assertEquals('Taylor Better Tester', $this->string->titleCase('TAYLOR beTteR TEsTer'));
		$this->assertEquals('Άχιστη', $this->string->titleCase('άχιστη'));
	}

	public function testStringCanBeLimitedByCharacters()
	{
		$this->assertEquals('Tay...', $this->string->limit('Taylor', 3));
		$this->assertEquals('Taylor', $this->string->limit('Taylor', 6));
		$this->assertEquals('Tay___', $this->string->limit('Taylor', 3, '___'));
	}

	public function testStringCanBeLimitedByCharactersIncludingElipses()
	{
		$this->assertEquals('T...', $this->string->limitExact('Taylor', 4));
		$this->assertEquals('Taylor', $this->string->limitExact('Taylor', 6));
		$this->assertEquals('Ta___', $this->string->limitExact('Taylor', 5, '___'));
	}

	public function testStringCanBeLimitedByWords()
	{
		$this->assertEquals('Taylor...', $this->string->limitWords('Taylor Otwell', 1));
		$this->assertEquals('Taylor___', $this->string->limitWords('Taylor Otwell', 1, '___'));
		$this->assertEquals('Taylor Otwell', $this->string->limitWords('Taylor Otwell', 3));
	}

	public function testStringCanBeWordWrapped()
	{
		$this->assertEquals('Robbo likes beer', $this->string->wordWrap('Robbo likes beer', 10));
		$this->assertEquals('Robbolikes beer', $this->string->wordWrap('Robbolikesbeer', 10));
		$this->assertEquals('Robbo likes beere speci allyw henit is hot!', $this->string->wordWrap('Robbolikesbeerespeciallywhenitis hot!', 5));
	}

	public function testStringsExtension()
	{
		$this->assertEquals('', $this->string->extension('My nEw post!!!'));
		$this->assertEquals('jpg', $this->string->extension('An img name To convert.jpg'));
		$this->assertEquals('blah', $this->string->extension('.An imgname.To-convert.blah'));
	}

	public function testStringsCanBeSlugged()
	{
		$this->assertEquals('my-new-post', $this->string->slug('My nEw post!!!'));
		$this->assertEquals('my_new_post', $this->string->slug('My nEw post!!!', '_'));
		$this->assertEquals('my-new-post', $this->string->slug('my-new-post'));
		$this->assertEquals('an-img-name-to-convertjpg', $this->string->slug('An img name To convert.jpg'));
		$this->assertEquals('an-img-name-to-convert.jpg', $this->string->slug('An img name To convert.jpg', '-', true));
	}

	public function testStringsCanBeConvertedToAscii()
	{
		$this->assertEquals('UzEJaPLae', $this->string->ascii('ŪžĒЯПĻæ'));
	}

	public function testStringsCanBeCamelCased()
	{
		$this->assertEquals('FooBar', $this->string->camelCase('foo_bar'));
		$this->assertEquals('FooBarBaz', $this->string->camelCase('foo-bar_baz'));
		$this->assertEquals('fooBar', $this->string->camelCase('foo_bar', false));
		$this->assertEquals('fooBarBaz', $this->string->camelCase('foo-bar_baz', false));
	}

	public function testStringCanBeSnakeCase()
	{
		$this->assertEquals('foo_bar', $this->string->snakeCase('fooBar'));
		$this->assertEquals('foo-bar', $this->string->snakeCase('fooBar', '-'));
	}

	public function testStringSegments()
	{
		$this->assertEquals($this->string->segments('a/path/of/words'), array(
			'a', 'path', 'of', 'words'
		));

		$this->assertEquals($this->string->segments('/a/path/of/words/'), array(
			'a', 'path', 'of', 'words'
		));
	}

	public function testRandomStringsCanBeGenerated()
	{
		$this->assertEquals(40, strlen($this->string->random(40)));
	}

	public function testStringStartsWith()
	{
		$this->assertTrue($this->string->startsWith('jason', 'jas'));
		$this->assertFalse($this->string->startsWith('jason', 'day'));
	}

	public function testStringEndsWith()
	{
		$this->assertTrue($this->string->endsWith('jason', 'on'));
		$this->assertFalse($this->string->endsWith('jason', 'no'));
	}

	public function testStringContains()
	{
		$this->assertTrue($this->string->contains('taylor', 'ylo'));
		$this->assertFalse($this->string->contains('taylor', 'xxx'));
	}

	public function testStringFinish()
	{
		$this->assertEquals('test string/', $this->string->finish('test string', '/'));
		$this->assertEquals('test stringBAM', $this->string->finish('test stringBAMBAM', 'BAM'));
		$this->assertEquals('test string/', $this->string->finish('test string/////', '/'));
	}
}