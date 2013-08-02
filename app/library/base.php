<?

class Base{
	private $base = 10;
	private $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_+=';
	private $len = 62;
	
	public function __construct( $chars = null ){
		$x = $chars ? $chars : $this->chars;
		$this->setAllowedChars( $x );
	}
	
	/*
	*	@param
	*	@return
	*/
	public function convertToBase10( $i ){
		return $this->baseToBase( $i, $this->len, 10 );
	}
	
	/*
	*	@param
	*	@return
	*/
	public function convertToBase( $i ){
		return $this->baseToBase( $i, 10, $this->len );
	}
	
	/*
	*	@param
	*	@return
	*/
	public function currentBase(){
		return $this->len;
	}
	
	/*
	*	@param
	*	@return
	*/
	public function currentChars(){
		return $this->chars;
	}
	
	/*
	*
	*	@param 
	*	@param 
	*	@param 
	*	@param 
	*	@return
	*/
	private function baseToBase( $iNum, $iBase, $oBase, $iScale = 0 ){

		if( $iBase != 10 ){
			$oNum = $this->baseToDec( $iNum, $iBase, $iScale );
		} else {
			$oNum = $iNum;
			$oNum = $this->decToBase( $oNum, $oBase, $iScale );
		}
	  	
	  	return $oNum;
	}
	
	/*
	*
	*	@param
	*	@param
	*	@param
	*	@return string - 32 bit system has limit too low for integer cast
	*/
	private function baseToDec( $sNum, $iBase = 0, $iScale = 0 ){
	
		$this->setBase( $iBase );
		$sResult = 0;
		
		//TODO: why did i have to put this in
		$sChars = '';
		
		// clean up the input string if it uses particular input formats
		switch( $this->base ){
			case 16: // remove 0x from start of string
				if( mb_strtolower(mb_substr($sNum, 0, 2)) == '0x' ) $sNum = mb_substr($sNum, 2);
				break;
			
			case 8: // remove the 0 from the start if it exists - not really required
				if( mb_strpos($sNum, '0') === 0 ) $sNum = mb_substr($sNum, 1);
				break;
			
			case 2: // remove an 0b from the start if it exists
				if( mb_strtolower(mb_substr($sNum, 0, 2)) == '0b' ) $sNum = mb_substr($sNum, 2);
				break;
		
			case 64: // remove padding chars: =
				$sNum = str_replace( '=', '', $sNum );
				break;
		
			default: // Look for numbers in the format base#number,
				// if so split it up and use the base from it
				if( mb_strpos($sNum, '#') !== false){
					list( $sBase, $sNum ) = explode( '#', $sNum, 2);
					$this->base = intval($sBase);  // take the new base
				}
				
				if( $this->base == 0 ){
					print("baseToDec called without a base value and not in base#number format" );
					return '';
				}
				break;
		}
		
		// Check to see if we are an integer or real number
		if( mb_strpos($sNum, '.') !== false){
			list( $sNum, $sReal) = explode( '.', $sNum, 2);
			$sReal = '0.' . $sReal;
		} else {
			$sReal = '0';
		}
		
		// By now we know we have a correct base and number
		$iLen = strlen( $sNum );
		
		// Now loop through each digit in the number
		for( $i=$iLen-1; $i>=0; $i-- ){
			$sChar = $sNum[$i]; // extract the last char from the number
			$iValue = strpos( $this->chars , $sChar); // get the decimal value
			if( $iValue > $iBase ){
				return '';
			}
			// Now convert the value+position to decimal
			$sResult = bcadd( $sResult, bcmul( $iValue, bcpow($this->base, ($iLen-$i-1))) );
		}
		
		// Now append the real part
		if( strcmp($sReal, '0') != 0 ){
			$sReal = mb_substr($sReal, 2); // Chop off the '0.' characters
			$iLen = strlen( $sReal );
			
			for( $i=0; $i<$iLen; $i++ ){
				$sChar = $sReal[$i]; // extract the first, second, third, etc char
				$iValue = mb_strpos( $sChars, $sChar); // get the decimal value
				if( $iValue > $this->base ){
					return '';
				}
				
				$sResult = bcadd( $sResult, bcdiv($iValue, bcpow($this->base, ($i+1)), $iScale), $iScale );
			}
		}
	
		return $sResult;
	}
	
	/*
	*	cope with base 2..62
	*	@param
	*	@param
	*	@param
	*	@return
	*/
	private function decToBase( $iNum, $iBase, $iScale = 0 ){ 
		$sResult = ''; // Store the result
		
		$this->setBase( $iBase );
		$sNum = (string) $iNum;
		
		if( mb_strpos($sNum, '.') !== FALSE ){
			list ($sNum, $sReal) = explode( '.', $sNum, 2);
			$sReal = '0.' . $sReal;
		} else {
			$sReal = '0';
    	}
    	
		while( bccomp($sNum, 0, $iScale) != 0 ){ // still data to process
			$sRem = bcmod( $sNum, $this->base ); // calc the remainder
			$sNum = bcdiv( bcsub($sNum, $sRem, $iScale), $this->base, $iScale );
			$sResult = $this->chars[$sRem] . $sResult;
		}
		
		if( $sReal != '0' ){
			$sResult .= '.';
			$fraciScale = $iScale;
			
			while( $fraciScale-- && bccomp($sReal, 0, $iScale) != 0 ){ // still data to process
				// multiple the float part with the base
				$sReal = bcmul($sReal, $iBase, $iScale); 
				$sFrac = 0;
				
				if( bccomp($sReal ,1, $iScale) > -1 ){
					// get the intval
					list($sFrac, $dummy) = explode( '.', $sReal, 2); 
				}
				
				$sResult .= $sChars[$sFrac];
				$sReal = bcsub($sReal, $sFrac, $iScale);
			}
		}
  
		return $sResult;
	}
	
	/*
	*
	*	@param
	*	@return
	*/
	private function setAllowedChars( $string = '' ){
		$aString = array_unique( preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY) );

		$this->chars = (string) implode('', ($aString) );
		$this->len = strlen( $this->chars );
	}
	
	/*
	*
	*	@param
	*	@return
	*/
	private function setBase( $iBase ){
		$this->base = (int) $iBase; // incase it is a string or some weird decimal
	}
}