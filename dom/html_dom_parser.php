<?php

/**
 * HTML DOM Parser
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License as published
 * by the Free Software Foundation - either version 3 of the License,
 * or (at your option) any later version - that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 * @category   lie2815
 * @package    htmldomparser
 * @copyright  Copyright (c) 2010 Franz Liedke (http://www.develophp.org),
 *             based on code (C) S.C. Chen <hide@address.com>
 *             (http://sourceforge.net/projects/simplehtmldom/)
 * @license    http://www.gnu.org/licenses/gpl.html	GPL General Public License
 */

define('HDOM_TYPE_ELEMENT', 1);
define('HDOM_TYPE_COMMENT', 2);
define('HDOM_TYPE_TEXT', 3);
define('HDOM_TYPE_ENDTAG', 4);
define('HDOM_TYPE_ROOT', 5);
define('HDOM_TYPE_UNKNOWN', 6);
define('HDOM_QUOTE_DOUBLE', 0);
define('HDOM_QUOTE_SINGLE', 1);
define('HDOM_QUOTE_NO', 3);
define('HDOM_INFO_BEGIN', 0);
define('HDOM_INFO_END', 1);
define('HDOM_INFO_QUOTE', 2);
define('HDOM_INFO_SPACE', 3);
define('HDOM_INFO_TEXT', 4);
define('HDOM_INFO_INNER', 5);
define('HDOM_INFO_OUTER', 6);
define('HDOM_INFO_ENDSPACE', 7);

// helper functions
// -----------------------------------------------------------------------------
// get html dom form file
function file_get_html()
{
    $dom = new Html_Dom;
    $args = func_get_args();
    @$dom->load(call_user_func_array('file_get_contents', $args), true);
    if (($error = error_get_last()) !== NULL)
        throw new Exception($error['message']);
    return $dom;
}

// get html dom form string
function str_get_html($str, $lowercase = true)
{
    $dom = new Html_Dom;
    $dom->load($str, $lowercase);
    return $dom;
}

// dump html dom tree
function dump_html_tree($node, $show_attr = true)
{
    $node->dump($node);
}

// get dom form file (deprecated)
function file_get_dom()
{
    $dom = new Html_Dom;
    $args = func_get_args();
    $dom->load(call_user_func_array('file_get_contents', $args), true);
    return $dom;
}

// get dom form string (deprecated)
function str_get_dom($str, $lowercase = true)
{
    $dom = new Html_Dom;
    $dom->load($str, $lowercase);
    return $dom;
}


/**
 * Class for DOM nodes of a HTML document
 *
 * @package htmldomparser
 */
class Html_Dom_Node
{
	const TYPE_ELEMENT = 1;
	const TYPE_COMMENT = 2;
	const TYPE_TEXT = 3;
	const TYPE_ENDTAG = 4;
	const TYPE_ROOT = 5;
	const TYPE_UNKNOWN = 6;
	
    public $nodetype = HDOM_TYPE_TEXT;
    public $tag = 'text';
    public $attr = array();
    public $children = array();
    public $nodes = array();
    public $parent = NULL;
    public $_ = array();
    private $dom = NULL;

    public function __construct($dom)
    {
        $this->dom = $dom;
        $dom->nodes[] = $this;
    }

    public function __destruct()
    {
        $this->clear();
    }

    public function __toString()
    {
        return $this->getOuterText();
    }

	/**
	 * Clean up memory
	 *
	 * This is necessary due to the circular references memory leak in PHP 5.
	 */
    public function clear()
    {
        $this->dom = NULL;
        $this->nodes = NULL;
        $this->parent = NULL;
        $this->children = NULL;
    }

	/**
	 * Dump the node's tree
	 *
	 * @param bool $show_attr
	 * @param int $deep
	 */
    public function dump($show_attr = true, $deep = 0)
    {
        $lead = str_repeat('    ', $deep);

        echo $lead . $this->tag;
        if ($show_attr && count($this->attr) > 0)
        {
            echo '(';
            foreach ($this->attr as $k => $v)
                echo "[$k]=>\"" . $this->$k . '", ';
            echo ')';
        }
        echo "\n";

        foreach ($this->nodes as $c)
            $c->dump($show_attr, $deep + 1);
    }

	/**
	 * Get the parent of the node
	 *
	 * @return Html_Dom_Node
	 */
    public function getParent()
    {
        return $this->parent;
    }

	/**
	 * Return all children of the node
	 *
	 * @return array
	 */
    public function getChildren()
    {
		return $this->children;
    }

	/**
	 * Return the specified child of the node
	 *
	 * @param int $idx
	 * @return Html_Dom_Node|null
	 */
	public function getChild($idx)
	{
		if (isset($this->children[$idx]))
			return $this->children[$idx];

		return NULL;
	}

	/**
	 * Get the first child of the node
	 *
	 * @return Html_Dom_Node|null
	 */
    public function getFirstChild()
    {
		return $this->getChild(0);
    }

	/**
	 * Get the last child of the node
	 *
	 * @return Html_Dom_Node|null
	 */
    public function getLastChild()
    {
        if (($count = count($this->children)) > 0)
            return $this->getChild($count - 1);
		
        return NULL;
    }

// returns the next sibling of node
    public function getNextSibling()
    {
        if ($this->parent === NULL)
            return NULL;
        $idx = 0;
        $count = count($this->parent->children);
        while ($idx < $count && $this !== $this->parent->children[$idx])
            ++$idx;
        if (++$idx >= $count)
            return NULL;
        return $this->parent->children[$idx];
    }

// returns the previous sibling of node
    public function getPrevSibling()
    {
        if ($this->parent === NULL)
            return NULL;
        $idx = 0;
        $count = count($this->parent->children);
        while ($idx < $count && $this !== $this->parent->children[$idx])
            ++$idx;
        if (--$idx < 0)
            return NULL;
        return $this->parent->children[$idx];
    }

// get dom node's inner html
    public function getInnerText()
    {
        if (isset($this->_[HDOM_INFO_INNER]))
            return $this->_[HDOM_INFO_INNER];
        if (isset($this->_[HDOM_INFO_TEXT]))
            return $this->dom->restoreNoise($this->_[HDOM_INFO_TEXT]);

        $ret = '';
        foreach ($this->nodes as $n)
            $ret .= $n->getOuterText();
        return $ret;
    }

// get dom node's outer text (with tag)
    public function getOuterText()
    {
        if ($this->tag === 'root')
            return $this->getInnerText();

// trigger callback
        if ($this->dom->callback !== NULL)
            call_user_func_array($this->dom->callback, array($this));

        if (isset($this->_[HDOM_INFO_OUTER]))
            return $this->_[HDOM_INFO_OUTER];
        if (isset($this->_[HDOM_INFO_TEXT]))
            return $this->dom->restoreNoise($this->_[HDOM_INFO_TEXT]);

// render begin tag
        $ret = $this->dom->nodes[$this->_[HDOM_INFO_BEGIN]]->makeup();

// render inner text
        if (isset($this->_[HDOM_INFO_INNER]))
            $ret .= $this->_[HDOM_INFO_INNER];
        else
        {
            foreach ($this->nodes as $n)
                $ret .= $n->getOuterText();
        }

// render end tag
        if (isset($this->_[HDOM_INFO_END]) && $this->_[HDOM_INFO_END]!=0)
            $ret .= '</' . $this->tag . '>';
        return $ret;
    }

// get dom node's plain text
    public function getText()
    {
        if (isset($this->_[HDOM_INFO_INNER]))
            return $this->_[HDOM_INFO_INNER];
        switch ($this->nodetype)
        {
            case HDOM_TYPE_TEXT: return $this->dom->restoreNoise($this->_[HDOM_INFO_TEXT]);
            case HDOM_TYPE_COMMENT: return '';
            case HDOM_TYPE_UNKNOWN: return '';
        }
        if (strcasecmp($this->tag, 'script') === 0)
            return '';
        if (strcasecmp($this->tag, 'style') === 0)
            return '';

        $ret = '';
        foreach ($this->nodes as $n)
            $ret .= $n->getText();
        return $ret;
    }

    public function getXmlText()
    {
        $ret = $this->getInnerText();
        $ret = str_ireplace('<![CDATA[', '', $ret);
        $ret = str_replace(']]>', '', $ret);
        return $ret;
    }

// build node's text with tag
    public function makeup()
    {
// text, comment, unknown
        if (isset($this->_[HDOM_INFO_TEXT]))
            return $this->dom->restoreNoise($this->_[HDOM_INFO_TEXT]);

        $ret = '<' . $this->tag;
        $i = -1;

        foreach ($this->attr as $key => $val)
        {
            ++$i;

// skip removed attribute
            if ($val === NULL || $val === false)
                continue;

            $ret .= $this->_[HDOM_INFO_SPACE][$i][0];
//no value attr: nowrap, checked selected...
            if ($val === true)
                $ret .= $key;
            else
            {
                switch ($this->_[HDOM_INFO_QUOTE][$i])
                {
                    case HDOM_QUOTE_DOUBLE: $quote = '"'; break;
                    case HDOM_QUOTE_SINGLE: $quote = '\''; break;
                    default: $quote = '';
                }
                $ret .= $key . $this->_[HDOM_INFO_SPACE][$i][1] . '=' . $this->_[HDOM_INFO_SPACE][$i][2] . $quote . $val . $quote;
            }
        }
        $ret = $this->dom->restoreNoise($ret);
        return $ret . $this->_[HDOM_INFO_ENDSPACE] . '>';
    }

// find elements by css selector
    public function find($selector, $idx = NULL)
    {
        $selectors = $this->parseSelector($selector);
        if (($count = count($selectors)) === 0)
            return array();
        $found_keys = array();

// find each selector
        for ($c = 0; $c < $count; ++$c)
        {
            if (($levle = count($selectors[0])) === 0)
                return array();
            if (!isset($this->_[HDOM_INFO_BEGIN]))
                return array();

            $head = array($this->_[HDOM_INFO_BEGIN] => 1);

// handle descendant selectors, no recursive!
            for ($l = 0; $l < $levle; ++$l)
            {
                $ret = array();
                foreach ($head as $k => $v)
                {
                    $n = ($k === - 1) ? $this->dom->root : $this->dom->nodes[$k];
                    $n->seek($selectors[$c][$l], $ret);
                }
                $head = $ret;
            }

            foreach ($head as $k => $v)
            {
                if (!isset($found_keys[$k]))
                    $found_keys[$k] = 1;
            }
        }

// sort keys
        ksort($found_keys);

        $found = array();
        foreach ($found_keys as $k => $v)
            $found[] = $this->dom->nodes[$k];

// return nth-element or array
        if (is_NULL($idx))
            return $found;
        else if ($idx < 0)
            $idx = count($found) + $idx;
        return (isset($found[$idx])) ? $found[$idx] : NULL;
    }

// seek for given conditions
    protected function seek($selector, &$ret)
    {
        list($tag, $key, $val, $exp, $no_key) = $selector;

// xpath index
        if ($tag && $key && is_numeric($key))
        {
            $count = 0;
            foreach ($this->children as $c)
            {
                if ($tag === '*' || $tag === $c->tag)
                {
                    if (++$count == $key)
                    {
                        $ret[$c->_[HDOM_INFO_BEGIN]] = 1;
                        return;
                    }
                }
            }
            return;
        }

        $end = (!empty($this->_[HDOM_INFO_END])) ? $this->_[HDOM_INFO_END] : 0;
        if ($end == 0)
        {
            $parent = $this->parent;
            while (!isset($parent->_[HDOM_INFO_END]) && $parent !== NULL)
            {
                $end -= 1;
                $parent = $parent->parent;
            }
            $end += $parent->_[HDOM_INFO_END];
        }

        for ($i = $this->_[HDOM_INFO_BEGIN] + 1; $i < $end; ++$i)
        {
            $node = $this->dom->nodes[$i];
            $pass = true;

            if ($tag === '*' && ! $key)
            {
                if (in_array($node, $this->children, true))
                    $ret[$i] = 1;
                continue;
            }

// compare tag
            if ($tag && $tag != $node->tag && $tag !== '*')
            {
                $pass = false;
            }
// compare key
            if ($pass && $key)
            {
                if ($no_key)
                {
                    if (isset($node->attr[$key]))
                        $pass = false;
                }
                else if (!isset($node->attr[$key]))
                    $pass = false;
            }
// compare value
            if ($pass && $key && $val && $val !== '*')
            {
                $check = $this->match($exp, $val, $node->attr[$key]);
// handle multiple class
                if (!$check && strcasecmp($key, 'class') === 0)
                {
                    foreach (explode(' ', $node->attr[$key]) as $k)
                    {
                        $check = $this->match($exp, $val, $k);
                        if ($check)
                            break;
                    }
                }
                if (!$check)
                    $pass = false;
            }
            if ($pass)
                $ret[$i] = 1;
            unset($node);
        }
    }

    protected function match($exp, $pattern, $value)
    {
        switch ($exp)
        {
            case '=':
                return ($value === $pattern);
            case '!=':
                return ($value !== $pattern);
            case '^=':
                return preg_match("/^" . preg_quote($pattern, '/') . "/", $value);
            case '$=':
                return preg_match("/" . preg_quote($pattern, '/') . "$/", $value);
            case '*=':
                if ($pattern[0] == '/')
                    return preg_match($pattern, $value);
                return preg_match("/" . $pattern . "/i", $value);
        }
        return false;
    }

    protected function parseSelector($selector_string)
    {
		// pattern of CSS selectors, modified from mootools
        $pattern = "/([\w-:\*]*)(?:\#([\w-]+)|\.([\w-]+))?(?:\[@?(!?[\w-]+)(?:([!*^$]?=)[\"']?(.*?)[\"']?)?\])?([\/, ]+)/is";
        preg_match_all($pattern, trim($selector_string) . ' ', $matches, PREG_SET_ORDER);
        $selectors = array();
        $result = array();
		//print_r($matches);

        foreach ($matches as $m)
        {
            $m[0] = trim($m[0]);
            if ($m[0] === '' || $m[0] === '/' || $m[0] === '//')
                continue;

			// for xpath generated by borwser
            if ($m[1] === 'tbody')
                continue;

            list($tag, $key, $val, $exp, $no_key) = array($m[1], NULL, NULL, '=', false);
            if (!empty($m[2]))
            {
                $key = 'id';
                $val = $m[2];
            }
            if (!empty($m[3]))
            {
                $key = 'class';
                $val = $m[3];
            }
            if (!empty($m[4]))
            {
                $key = $m[4];
            }
            if (!empty($m[5]))
            {
                $exp = $m[5];
            }
            if (!empty($m[6]))
            {
                $val = $m[6];
            }

			// convert to lowercase
            if ($this->dom->lowercase)
            {
                $tag = strtolower($tag);
                $key = strtolower($key);
            }
			//elements that do NOT have the specified attribute
            if (isset($key[0]) && $key[0] === '!')
            {
                $key = substr($key, 1);
                $no_key = true;
            }

            $result[] = array($tag, $key, $val, $exp, $no_key);
            if (trim($m[7]) === ',')
            {
                $selectors[] = $result;
                $result = array();
            }
        }
        if (count($result) > 0)
            $selectors[] = $result;
        return $selectors;
    }

    public function __get($name)
    {
        if (isset($this->attr[$name]))
            return $this->attr[$name];
        switch ($name)
        {
            case 'outertext': return $this->getOuterText();
            case 'innertext': return $this->getInnerText();
            case 'plaintext': return $this->getText();
            case 'xmltext': return $this->getXmlText();
            default: return array_key_exists($name, $this->attr);
        }
    }

    public function __set($name, $value)
    {
        switch ($name)
        {
            case 'outertext': return $this->_[HDOM_INFO_OUTER] = $value;
            case 'innertext':
                if (isset($this->_[HDOM_INFO_TEXT]))
                    return $this->_[HDOM_INFO_TEXT] = $value;
                return $this->_[HDOM_INFO_INNER] = $value;
        }
        if (!isset($this->attr[$name]))
        {
            $this->_[HDOM_INFO_SPACE][] = array(' ', '', '');
            $this->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_DOUBLE;
        }
        $this->attr[$name] = $value;
    }

    public function __isset($name)
    {
        switch ($name)
        {
            case 'outertext': return true;
            case 'innertext': return true;
            case 'plaintext': return true;
        }
		//no value attr: nowrap, checked selected...
        return (array_key_exists($name, $this->attr)) ? true : isset($this->attr[$name]);
    }

    public function __unset($name)
    {
        if (isset($this->attr[$name]))
            unset($this->attr[$name]);
    }

	// camel naming conventions
    public function getAllAttributes()
    {
        return $this->attr;
    }

    public function getAttribute($name)
    {
        return $this->__get($name);
    }

    public function setAttribute($name, $value)
    {
        $this->__set($name, $value);
    }

    public function hasAttribute($name)
    {
        return $this->__isset($name);
    }

    public function removeAttribute($name)
    {
        $this->__set($name, NULL);
    }

    public function getElementById($id)
    {
        return $this->find("#$id", 0);
    }

    public function getElementsById($id, $idx = NULL)
    {
        return $this->find("#$id", $idx);
    }

    public function getElementByTagName($name)
    {
        return $this->find($name, 0);
    }

    public function getElementsByTagName($name, $idx = NULL)
    {
        return $this->find($name, $idx);
    }

    public function parentNode()
    {
        return $this->getParent();
    }
}

/**
 * A HTML document consisting of nodes
 *
 * @uses Html_Dom_Node
 * @package htmldomparser
 */
class Html_Dom
{
    /**
     * The root node of the document
     *
     * @var Html_Dom_Node
     */
    public $root = NULL;
    public $nodes = array();
    public $callback = NULL;
    public $lowercase = false;
    protected $pos;
    protected $doc;
    protected $char;
    protected $size;
    protected $cursor;
    protected $parent;
    protected $noise = array();
    protected $token_blank = " \t\r\n";
    protected $token_equal = ' =/>';
    protected $token_slash = " />\r\n\t";
    protected $token_attr = ' >';
	// use isset instead of in_array, performance boost about 30%...
    protected $self_closing_tags = array('img' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'link' => 1, 'hr' => 1, 'base' => 1, 'embed' => 1, 'spacer' => 1);
    protected $block_tags = array('root' => 1, 'body' => 1, 'form' => 1, 'div' => 1, 'span' => 1, 'table' => 1);
    protected $optional_closing_tags = array(
        'tr' => array('tr' => 1, 'td' => 1, 'th' => 1),
        'th' => array('th' => 1),
        'td' => array('td' => 1),
        'li' => array('li' => 1),
        'dt' => array('dt' => 1, 'dd' => 1),
        'dd' => array('dd' => 1, 'dt' => 1),
        'dl' => array('dd' => 1, 'dt' => 1),
        'p' => array('p' => 1),
        'nobr' => array('nobr' => 1),
    );

    /**
     * Constructor
     *
     * Create a DOM document object from the optionally given file or string
     *
     * @var string $str  The filename of a file or a string to be parsed
     */
    public function __construct($str = NULL)
    {
        if ($str)
        {
            if (preg_match("/^http:\/\//i", $str) || is_file($str))
                $this->loadFile($str);
            else
                $this->load($str);
        }
    }

    public function __destruct()
    {
        $this->clear();
    }

	// load html from string
    public function load($str, $lowercase = true)
    {
		// prepare
        $this->prepare($str, $lowercase);
		// strip out comments
        $this->removeNoise("'<!--(.*?)-->'is");
		// strip out cdata
        $this->removeNoise("'<!\[CDATA\[(.*?)\]\]>'is", true);
		// strip out <style> tags
        $this->removeNoise("'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is");
        $this->removeNoise("'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is");
		// strip out <script> tags
        $this->removeNoise("'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is");
        $this->removeNoise("'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is");
		// strip out preformatted tags
        $this->removeNoise("'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is");
		// strip out server side scripts
        $this->removeNoise("'(<\?)(.*?)(\?>)'s", true);
		// strip smarty scripts
        $this->removeNoise("'(\{\w)(.*?)(\})'s", true);

		// parsing
        while ($this->parse()
            );

		// end
        $this->root->_[HDOM_INFO_END] = $this->cursor;
    }

	// load html from file
    public function loadFile()
    {
        $args = func_get_args();
        @$this->load(call_user_func_array('file_get_contents', $args), true);
        if (($error = error_get_last()) !== NULL)
            throw new Exception($error['message']);
    }

	// set callback function
    public function setCallback($function_name)
    {
        $this->callback = $function_name;
    }

	// remove callback function
    public function removeCallback()
    {
        $this->callback = NULL;
    }

	// save dom as string
    public function save($filepath = '')
    {
        $ret = $this->root->getInnerText();
        if ($filepath !== '')
            file_put_contents($filepath, $ret, LOCK_EX);
        return $ret;
    }

	// find dom node by css selector
    public function find($selector, $idx = NULL)
    {
        return $this->root->find($selector, $idx);
    }

	// clean up memory due to php5 circular references memory leak...
    public function clear()
    {
        foreach ($this->nodes as $n)
        {
            $n->clear();
            $n = NULL;
        }
        if (isset($this->parent))
        {
            $this->parent->clear();
            unset($this->parent);
        }
        if (isset($this->root))
        {
            $this->root->clear();
            unset($this->root);
        }
        unset($this->doc);
        unset($this->noise);
    }

    public function dump($show_attr = true)
    {
        $this->root->dump($show_attr);
    }

	// prepare HTML data and init everything
    protected function prepare($str, $lowercase = true)
    {
        $this->clear();
        $this->doc = $str;
        $this->pos = 0;
        $this->cursor = 1;
        $this->noise = array();
        $this->nodes = array();
        $this->lowercase = $lowercase;
        $this->root = new Html_Dom_Node($this);
        $this->root->tag = 'root';
        $this->root->_[HDOM_INFO_BEGIN] = -1;
        $this->root->nodetype = HDOM_TYPE_ROOT;
        $this->parent = $this->root;
		// set the length of content
        $this->size = strlen($str);
        if ($this->size > 0)
            $this->char = $this->doc[0];
    }

	// parse html content
    protected function parse()
    {
        if (($s = $this->copyUntilChar('<')) === '')
            return $this->readTag();

		// text
        $node = new Html_Dom_Node($this);
        ++$this->cursor;
        $node->_[HDOM_INFO_TEXT] = $s;
        $this->linkNodes($node, false);
        return true;
    }

	// read tag info
    protected function readTag()
    {
        if ($this->char !== '<')
        {
            $this->root->_[HDOM_INFO_END] = $this->cursor;
            return false;
        }
        $begin_tag_pos = $this->pos;
        $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
		// end tag
        if ($this->char === '/')
        {
            $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
            $this->skip($this->token_blank);
            $tag = $this->copyUntilChar('>');

			// skip attributes in end tag
            if (($pos = strpos($tag, ' ')) !== false)
                $tag = substr($tag, 0, $pos);

            $parent_lower = strtolower($this->parent->tag);
            $tag_lower = strtolower($tag);

            if ($parent_lower !== $tag_lower)
            {
                if (isset($this->optional_closing_tags[$parent_lower]) && isset($this->block_tags[$tag_lower]))
                {
                    $this->parent->_[HDOM_INFO_END] = 0;
                    $org_parent = $this->parent;

                    while (($this->parent->parent) && strtolower($this->parent->tag) !== $tag_lower)
                        $this->parent = $this->parent->parent;

                    if (strtolower($this->parent->tag) !== $tag_lower)
                    {
                        $this->parent = $org_parent; // restore origonal parent
                        if ($this->parent->parent)
                            $this->parent = $this->parent->parent;
                        $this->parent->_[HDOM_INFO_END] = $this->cursor;
                        return $this->asTextNode($tag);
                    }
                }
                else if (($this->parent->parent) && isset($this->block_tags[$tag_lower]))
                {
                    $this->parent->_[HDOM_INFO_END] = 0;
                    $org_parent = $this->parent;

                    while (($this->parent->parent) && strtolower($this->parent->tag) !== $tag_lower)
                        $this->parent = $this->parent->parent;

                    if (strtolower($this->parent->tag) !== $tag_lower)
                    {
                        $this->parent = $org_parent; // restore origonal parent
                        $this->parent->_[HDOM_INFO_END] = $this->cursor;
                        return $this->asTextNode($tag);
                    }
                } else if (($this->parent->parent) && strtolower($this->parent->parent->tag) === $tag_lower)
                {
                    $this->parent->_[HDOM_INFO_END] = 0;
                    $this->parent = $this->parent->parent;
                }
                else
                    return $this->asTextNode($tag);
            }

            $this->parent->_[HDOM_INFO_END] = $this->cursor;
            if ($this->parent->parent)
                $this->parent = $this->parent->parent;

            $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
            return true;
        }

        $node = new Html_Dom_Node($this);
        $node->_[HDOM_INFO_BEGIN] = $this->cursor;
        ++$this->cursor;
        $tag = $this->copyUntil($this->token_slash);

		// doctype, cdata & comments...
        if (isset($tag[0]) && $tag[0] === '!')
        {
            $node->_[HDOM_INFO_TEXT] = '<' . $tag . $this->copyUntilChar('>');

            if (isset($tag[2]) && $tag[1] === '-' && $tag[2] === '-')
            {
                $node->nodetype = HDOM_TYPE_COMMENT;
                $node->tag = 'comment';
            } else
            {
                $node->nodetype = HDOM_TYPE_UNKNOWN;
                $node->tag = 'unknown';
            }

            if ($this->char === '>')
                $node->_[HDOM_INFO_TEXT] .= '>';
            $this->linkNodes($node, true);
            $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
            return true;
        }

		// text
        if ($pos = strpos($tag, '<') !== false)
        {
            $tag = '<' . substr($tag, 0, -1);
            $node->_[HDOM_INFO_TEXT] = $tag;
            $this->linkNodes($node, false);
            $this->char = $this->doc[--$this->pos]; // prev
            return true;
        }

        if (!preg_match("/^[\w-:]+$/", $tag))
        {
            $node->_[HDOM_INFO_TEXT] = '<' . $tag . $this->copyUntil('<>');
            if ($this->char === '<')
            {
                $this->linkNodes($node, false);
                return true;
            }

            if ($this->char === '>')
                $node->_[HDOM_INFO_TEXT] .= '>';
            $this->linkNodes($node, false);
            $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
            return true;
        }

		// begin tag
        $node->nodetype = HDOM_TYPE_ELEMENT;
        $tag_lower = strtolower($tag);
        $node->tag = ($this->lowercase) ? $tag_lower : $tag;

		// handle optional closing tags
        if (isset($this->optional_closing_tags[$tag_lower]))
        {
            while (isset($this->optional_closing_tags[$tag_lower][strtolower($this->parent->tag)]))
            {
                $this->parent->_[HDOM_INFO_END] = 0;
                $this->parent = $this->parent->parent;
            }
            $node->parent = $this->parent;
        }

        $guard = 0; // prevent infinity loop
        $space = array($this->copySkip($this->token_blank), '', '');

		// attributes
        do
        {
            if ($this->char !== NULL && $space[0] === '')
                break;
            $name = $this->copyUntil($this->token_equal);
            if ($guard === $this->pos)
            {
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
                continue;
            }
            $guard = $this->pos;

			// handle endless '<'
            if ($this->pos >= $this->size - 1 && $this->char !== '>')
            {
                $node->nodetype = HDOM_TYPE_TEXT;
                $node->_[HDOM_INFO_END] = 0;
                $node->_[HDOM_INFO_TEXT] = '<' . $tag . $space[0] . $name;
                $node->tag = 'text';
                $this->linkNodes($node, false);
                return true;
            }

			// handle mismatch '<'
            if ($this->doc[$this->pos - 1]=='<')
            {
                $node->nodetype = HDOM_TYPE_TEXT;
                $node->tag = 'text';
                $node->attr = array();
                $node->_[HDOM_INFO_END] = 0;
                $node->_[HDOM_INFO_TEXT] = substr($this->doc, $begin_tag_pos, $this->pos - $begin_tag_pos - 1);
                $this->pos -= 2;
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
                $this->linkNodes($node, false);
                return true;
            }

            if ($name !== '/' && $name !== '')
            {
                $space[1] = $this->copySkip($this->token_blank);
                $name = $this->restoreNoise($name);
                if ($this->lowercase)
                    $name = strtolower($name);
                if ($this->char === '=')
                {
                    $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
                    $this->parseAttr($node, $name, $space);
                } else
                {
					//no value attr: nowrap, checked selected...
                    $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_NO;
                    $node->attr[$name] = true;
                    if ($this->char != '>')
                        $this->char = $this->doc[--$this->pos]; // prev
                }
                $node->_[HDOM_INFO_SPACE][] = $space;
                $space = array($this->copySkip($this->token_blank), '', '');
            }
            else
                break;
        } while ($this->char !== '>' && $this->char !== '/');

        $this->linkNodes($node, true);
        $node->_[HDOM_INFO_ENDSPACE] = $space[0];

		// check self closing
        if ($this->copyUntilCharEscape('>') === '/')
        {
            $node->_[HDOM_INFO_ENDSPACE] .= '/';
            $node->_[HDOM_INFO_END] = 0;
        } else
        {
			// reset parent
            if (!isset($this->self_closing_tags[strtolower($node->tag)]))
                $this->parent = $node;
        }
        $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
        return true;
    }

	// parse attributes
    protected function parseAttr($node, $name, &$space)
    {
        $space[2] = $this->copySkip($this->token_blank);
        switch ($this->char)
        {
            case '"':
                $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_DOUBLE;
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
                $node->attr[$name] = $this->restoreNoise($this->copyUntilCharEscape('"'));
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
                break;
            case '\'':
                $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_SINGLE;
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
                $node->attr[$name] = $this->restoreNoise($this->copyUntilCharEscape('\''));
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
                break;
            default:
                $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_NO;
                $node->attr[$name] = $this->restoreNoise($this->copyUntil($this->token_attr));
        }
    }

	// link node's parent
    protected function linkNodes(&$node, $is_child)
    {
        $node->parent = $this->parent;
        $this->parent->nodes[] = $node;
        if ($is_child)
            $this->parent->children[] = $node;
    }

	// as a text node
    protected function asTextNode($tag)
    {
        $node = new Html_Dom_Node($this);
        ++$this->cursor;
        $node->_[HDOM_INFO_TEXT] = '</' . $tag . '>';
        $this->linkNodes($node, false);
        $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
        return true;
    }

    protected function skip($chars)
    {
        $this->pos += strspn($this->doc, $chars, $this->pos);
        $this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
    }

    protected function copySkip($chars)
    {
        $pos = $this->pos;
        $len = strspn($this->doc, $chars, $pos);
        $this->pos += $len;
        $this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
        if ($len === 0)
            return '';
        return substr($this->doc, $pos, $len);
    }

    protected function copyUntil($chars)
    {
        $pos = $this->pos;
        $len = strcspn($this->doc, $chars, $pos);
        $this->pos += $len;
        $this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : NULL; // next
        return substr($this->doc, $pos, $len);
    }

    protected function copyUntilChar($char)
    {
        if ($this->char === NULL)
            return '';

        if (($pos = strpos($this->doc, $char, $this->pos)) === false)
        {
            $ret = substr($this->doc, $this->pos, $this->size - $this->pos);
            $this->char = NULL;
            $this->pos = $this->size;
            return $ret;
        }

        if ($pos === $this->pos)
            return '';
        $pos_old = $this->pos;
        $this->char = $this->doc[$pos];
        $this->pos = $pos;
        return substr($this->doc, $pos_old, $pos - $pos_old);
    }

    protected function copyUntilCharEscape($char)
    {
        if ($this->char === NULL)
            return '';

        $start = $this->pos;
        while (1)
        {
            if (($pos = strpos($this->doc, $char, $start)) === false)
            {
                $ret = substr($this->doc, $this->pos, $this->size - $this->pos);
                $this->char = NULL;
                $this->pos = $this->size;
                return $ret;
            }

            if ($pos === $this->pos)
                return '';

            if ($this->doc[$pos - 1] === '\\')
            {
                $start = $pos + 1;
                continue;
            }

            $pos_old = $this->pos;
            $this->char = $this->doc[$pos];
            $this->pos = $pos;
            return substr($this->doc, $pos_old, $pos - $pos_old);
        }
    }

	// remove noise from html content
	protected function removeNoise($pattern, $remove_tag=false)
    {
        $count = preg_match_all($pattern, $this->doc, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        for ($i = $count - 1; $i > -1; --$i)
        {
            $key = '___noise___' . sprintf('% 3d', count($this->noise) + 100);
            $idx = ($remove_tag) ? 0 : 1;
            $this->noise[$key] = $matches[$i][$idx][0];
            $this->doc = substr_replace($this->doc, $key, $matches[$i][$idx][1], strlen($matches[$i][$idx][0]));
        }

		// reset the length of content
        $this->size = strlen($this->doc);
        if ($this->size > 0)
            $this->char = $this->doc[0];
    }

	// restore noise to html content
    public function restoreNoise($text)
    {
        while (($pos = strpos($text, '___noise___'))!==false)
        {
            $key = '___noise___' . $text[$pos + 11] . $text[$pos + 12] . $text[$pos + 13];
            if (isset($this->noise[$key]))
                $text = substr($text, 0, $pos) . $this->noise[$key] . substr($text, $pos + 14);
        }
        return $text;
    }

    public function __toString()
    {
        return $this->root->getInnerText();
    }

    public function __get($name)
    {
        switch ($name)
        {
            case 'outertext': return $this->root->getInnerText();
            case 'innertext': return $this->root->getInnerText();
            case 'plaintext': return $this->root->getText();
        }
    }

	public function getChild($idx)
	{
		return $this->root->getChild($idx);
	}

	public function getChildren()
	{
		return $this->root->getChildren();
	}

    public function getFirstChild()
    {
        return $this->root->getFirstChild();
    }

    public function getLastChild()
    {
        return $this->root->getLastChild();
    }

    public function getElementById($id)
    {
        return $this->find("#$id", 0);
    }

    public function getElementsById($id, $idx = NULL)
    {
        return $this->find("#$id", $idx);
    }

    public function getElementByTagName($name)
    {
        return $this->find($name, 0);
    }

    public function getElementsByTagName($name, $idx = -1)
    {
        return $this->find($name, $idx);
    }
}

?>