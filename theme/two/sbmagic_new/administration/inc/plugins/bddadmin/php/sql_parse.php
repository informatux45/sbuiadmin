<?php

	class turboSqlParser
	{
		var $debug;
		function oob()
		{
			return ($this->p < 0 || $this->p >= $this->l);
		}
		
		function search($inRegEx)
		{
			//if ($this->debug)
			//	print_r('searching for: ' . $inRegEx . '<br>');
			$matches = false;
			preg_match($inRegEx, $this->s, $matches, PREG_OFFSET_CAPTURE, $this->p0);
			$this->p = ($matches ? $matches[0][1] : -1);
			if ($this->oob())
			 return false;
			//$this->d = substr($this->s, $this->p, 1);
			$this->d = $this->s{$this->p};
			if ($this->debug)
				print_r('found: ' . $this->d . ' at ' . $this->p . '<br>');
			return true;
		}
		
		function tokenize($inD = 0)
		{
			$this->p += $inD;
			if ($this->p > $this->p0)
			{
				$tok = substr($this->s, $this->p0, $this->p - $this->p0);
				$this->t .= $tok;
				if ($this->debug && $this->t && $tok) 
					print_r('token: [' . $this->t . ']<br>');
				//$this->s = substr($this->s, $this->p);
				//$this->l = strlen($this->s);
				$this->p0 = $this->p;
			}
		}
			
		function pusht()
		{
			if ($this->t)
				array_push($this->r, $this->t);
		}
		
		function pushToken($inD = 0)
		{
			$this->tokenize($inD);
			$this->pusht();
			$this->t = '';
		}
		
		function start($inS)
		{
			$this->debug = false;
			$this->s = $inS;
			$this->l = strlen($this->s);
			$this->r = array();
			$this->d = '';
			$this->p0 = 0;
			$this->p = 0;
			$this->t0 = 0;
			$this->t = '';
			$this->analyze();
		}
		
		function finish()
		{
			// FIXME: SJM please review. 1 character was being removed from end of last token
			// so added logic to prevent a negative length param for substr
			if ($this->p - $this->p0 > 0)
				$this->t .= substr($this->s, $this->p0, $this->p - $this->p0);
			else	
				$this->t .= substr($this->s, $this->p0);
			$this->pusht();
		}
		
		function do_separator()
		{
			if ($this->debug)
				print_r('doSeparator:<br>');
			$this->tokenize(1);
		}
		
		function do_escape()
		{
			if ($this->debug)
				print_r('doEscape:<br>');
			$this->tokenize(2);
		}
		
		function do_sql_comment()
		{
			if ($this->debug)
				print_r('doDashComment:<br>');
			$this->tokenize(($this->d == '-' ? 2 : 1));
			if ($this->search('/[\n]/'))
				$this->tokenize(1);
		}
		
		function do_c_comment()
		{
			if ($this->debug)
				print_r('doCComment:<br>');
			$this->tokenize(2);
			$n = 1;
			while ($n && $this->search('/\/\*|\*\//'))
			{
				switch ($this->d) {
					case '/':
						$n++;
						break;
					case '*':
						$n--;
						break;
				}
				$this->tokenize(2);
			}
		}
		 
		function do_literal()
		{
			if ($this->debug)
				print_r('doLiteral:<br>');
			$this->tokenize(1);
			$rx = "/" . $this->d . "|\\\\" . "/";
			while ($this->search($rx))
			{
				switch ($this->d) {
					case '\\':
						$this->do_escape();
						break;
					default:
						$this->tokenize(1);
						return;
				}
			}
		} 
		
		function analyze()
		{
			while ($this->search("/--|\/\*|[#'\"`\\;]/"))
			{
				if ($this->d == '\\')
					$this->do_escape();
				else
				{
					$this->pushToken();
					switch ($this->d)
					{
						case ';':
							$this->do_separator();
							break;
						case '/': 
							$this->do_c_comment();
							break;
						case '-': 
						case '#':
							$this->do_sql_comment();
							break;
						default: 
							$this->do_literal();
							break;
					}
					$this->pushToken();
				}
			}
			$this->finish();
		}

		function split_sql($inSql)
		{
			$t = ';';
			$this->start($inSql);
			$tokens = $this->r;
			$result = array();
			$x = array();
			foreach ($tokens as $token)
			{
				if ($token != $t)
					array_push($x, $token);
				else
				{
					$b = trim(join('', $x));
					if ($b)
						array_push($result, $b);
					$x = array();
				}
			}
			
			$b = trim(join('', $x));
			if ($b && $b != $t)
				array_push($result, $b);
			return $result;	
		}
		
		function is_bad_command($inCmd)
		{
			$matches = null;
			$this->start($inCmd);
			foreach ($this->r as $token)
			{
				preg_match('/^(--|\/\*|[#\'"`\\;])/', $token, $matches);
				if (count($matches))
					continue;
				preg_match('/DROP.*DATABASE/i', $token, $matches);	
				if (count($matches))
					return true;
				preg_match('/LOAD|DATA\\s|INFILE/i', $token, $matches);	
				if (count($matches))
					return true;	
			}
			return false;
		}
	}
	
	$sql_parser = new turboSqlParser();
?>