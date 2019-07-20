<?php
namespace XSYD\Tools\;


/**
 * 
 */
class General
{
	/**
	* 根据键值删除array中的值
	* @version 0.1
	* @param array $array 需要处理的array
	* @param array/string $key 需要删除的键值，可为array或者string
	*
	*/
	public DeleteArrayValuesByKey(array $array , $key){
       return is_array($key) ? array_diff_key($array,array_fill_keys($key,$key)) : array_slice($array, array_search($key,array_keys($array))+1);
	}
}


?>