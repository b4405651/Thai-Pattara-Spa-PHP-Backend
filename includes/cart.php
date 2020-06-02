<?php
class CART {
	private $session;
	
	public function __construct()
	{
		error_reporting(E_ALL);
		define( '_JEXEC', 1 );
		define('JPATH_BASE', __DIR__);
		
		//echo JPATH_BASE . '\defines.php<BR>';
		//echo JPATH_BASE . '\framework.php<BR>';
		
		if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
		define( 'JPATH_CONFIGURATION',    JPATH_BASE . DS . '..' . DS );
		define('JPATH_LIBRARIES', JPATH_BASE . DS . '..' . DS . 'libraries');
		
		require_once ( JPATH_BASE . '/defines.php' );
		require_once ( JPATH_BASE . '/framework.php' );
		
		$mainframe = JFactory::getApplication('site');
		$mainframe->initialise();
		$this->session = JFactory::getSession();
	}
	
	function getCart(&$total, &$result_array)
	{
		$result_array = array();
		if($this->session->get('cart') != null){
			foreach($this->session->get('cart') as $item){
				$total += $item["PRICE"];
				array_push($result_array, array("id" => $item["id"], "NAME" => $item["NAME"], "PRICE" => $item["PRICE"]));
			}
		}
	}
	
	public function view()
	{
		$total = 0;
		$result_array = array();
		$this->getCart($total, $result_array);
		
		return json_encode(array('type'=>'success', 'count' => count($this->session->get('cart')), 'total' => number_format($total), 'RESULT' => $result_array));
	}
	
	public function push($name, $price)
	{
		$arr = $this->session->get('cart');
		if($arr == null) $arr = array();
		$id = count($arr) + 1;
		array_push($arr, array("id" => $id, "NAME" => $name, "PRICE" => $price));
		$this->session->set('cart', $arr);

		$total = 0;
		$result_array = array();
		$this->getCart($total, $result_array);

		return json_encode(array('type'=>'success', 'count' => count($this->session->get('cart')), 'total' => number_format($total), 'RESULT' => $result_array));
	}
	
	public function pop($remove_id)
	{
		$remove_index = -1;

		$arr = $this->session->get('cart');
		if($arr != null){
			$index = -1;
			foreach($arr as $arr_1){
				$index++;
				foreach($arr_1 as $key_1 => $val_1){
					if($key_1 == 'id' && $val_1 == $remove_id){
						$remove_index = $index;
						break;
					}
				}
				
				if($remove_index > -1) {
					array_splice($arr, $remove_index, 1);
					break;
				}
			}
			$this->session->set('cart', $arr);
		}

		$total = 0;
		$result_array = array();
		$this->getCart($total, $result_array);
		return json_encode(array('type'=>'success', 'count' => count($this->session->get('cart')), 'total' => number_format($total), 'RESULT' => $result_array));
	}
	
	public function clear()
	{
		$this->session->destroy();
		return json_encode(array('type'=>'success', 'count' => 0, 'total' => 0, 'RESULT' => null));
	}
}
?>