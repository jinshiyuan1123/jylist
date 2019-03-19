<?php
	function print_stack_trace()
	{
		$array = debug_backtrace();
		foreach($array as $row)
		{
			$html .= "<p>" . $row['file'] . ':' . $row['line'] . '行,调用方法:' . $row['function'] . "</p>";
		}

		return $html;
	}
