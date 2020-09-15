<?php

/*
 * crypto.php -> phpFreaksCrypto Class (PHP4)
 * http://www.phpfreaks.com/tutorials/128/1.php
 */

/**
  * @author Dustin Whittle
  * @version 0.01
  */

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
  // tell people trying to access this file directly goodbye...
  exit('This file can not be accessed directly...');
}

set_include_path(get_include_path() . PATH_SEPARATOR . 'includes/classes/phpseclib');
include('mcrypt.php');
include('Crypt/Common/SymmetricKey.php');
include('Crypt/Common/BlockCipher.php');
include('Crypt/DES.php');
include('Crypt/Common/StreamCipher.php');
include('Crypt/Rijndael.php');
include('Crypt/Twofish.php');
include('Crypt/Blowfish.php');
include('Crypt/TripleDES.php');
include('Crypt/RC2.php');
include('Crypt/RC4.php');
include('Crypt/Random.php');


class phpFreaksCrypto
{

  //var $td;

  // this gets called when class is instantiated
  function __construct($key = 'a843l?nv89rjfd}O(jdnsleken0', $iv = false, $algorithm = 'tripledes', $mode = 'ecb')
  {

    // set mcrypt mode and cipher
    $this->td = mcrypt_module_open($algorithm, '', $mode, '') ;

    // Unix has better pseudo random number generator then mcrypt, so if it is available lets use it!
    //$random_seed = strstr(PHP_OS, "WIN") ? MCRYPT_RAND : MCRYPT_DEV_RANDOM;
    $random_seed = MCRYPT_RAND;

    // if initialization vector set in constructor use it else, generate from random seed
    $iv = ($iv === false) ? mcrypt_create_iv(mcrypt_enc_get_iv_size($this->td), $random_seed) : substr($iv, 0, mcrypt_enc_get_iv_size($this->td));

    // get the expected key size based on mode and cipher
    $expected_key_size = mcrypt_enc_get_key_size($this->td);

    // we dont need to know the real key, we just need to be able to confirm a hashed version
    $key = substr(md5($key), 0, $expected_key_size);

    // initialize mcrypt library with mode/cipher, encryption key, and random initialization vector
    mcrypt_generic_init($this->td, $key, $iv);
  }

  function encrypt($plain_string)
  {
    /*
      encrypt string using mcrypt and then encode any special characters
      and then return the encrypted string
    */
    return base64_encode(mcrypt_generic($this->td, $plain_string));
  }

  function decrypt($encrypted_string)
  {
    /*
      remove any special characters then decrypt string using mcrypt and then trim null padding
      and then finally return the encrypted string
    */
    return trim(mdecrypt_generic($this->td, base64_decode($encrypted_string)));
  }

  // since php 4 does not have deconstructors, we will need to manually call this function
  function __destruct()
  {
    // shutdown mcrypt
    mcrypt_generic_deinit($this->td);

    // close mcrypt cipher module
    mcrypt_module_close($this->td);
  }

}
