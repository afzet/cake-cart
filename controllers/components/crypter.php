<?php
class CrypterComponent extends Object {
    /**
     * ! WARNING ! WARNING ! WARNING ! WARNING ! WARNING ! WARNING ! WARNING !
     *
     * Do not change this key after we are in production unless you know what
     * the hell you are doing!!  If you change this key, no one can process
     * payments!
     *
     * If you want to change your key every so often, decrypt all the data that
     * has been previously encrypted and store as plain text somewhere, change
     * the key and then re-encrypt it.
     *
     * ! WARNING ! WARNING ! WARNING ! WARNING ! WARNING ! WARNING ! WARNING !
     */
    var $key = '3nl38927(*^$&#@(nbu*2oo';

    var $name = "Crypter";
    /**
     * This function will encrypt the string that is passed to it
     *
     * @param String $data The string to be encrypted.
     * @return String Returns the encrypted string or false
     * @access public
     */
    function enCrypt($data = null) {
        if ($data != null) {
            // Make an encryption resource using a cipher
            $td = mcrypt_module_open('cast-256', '', 'ecb', '');
            // Create and encryption vector based on the $td size and random
            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
            // Initialize the module using the resource, my key and the string vector
            mcrypt_generic_init($td, $this->key, $iv);
            // Encrypt the data using the $td resource
            $encrypted_data = mcrypt_generic($td, $data);
            // Encode in base64 for DB storage
            $encoded = base64_encode($encrypted_data);
            // Make sure the encryption modules get un-loaded
            if (!mcrypt_generic_deinit($td) || !mcrypt_module_close($td)) {
                $encoded = false;
            }
        } else {
            $encoded = false;
        }
        return $encoded;
    }
    /**
     * This function will de-crypt the string that is passed to it
     *
     * @param String $data The string to be encrypted.
     * @return String Returns the encrypted string or false
     */
    function deCrypt($data = null) {
        if ($data != null) {
            // The reverse of encrypt.  See that function for details
            $data = (string) base64_decode(trim($data));
            $td = mcrypt_module_open('cast-256', '', 'ecb', '');
            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
            mcrypt_generic_init($td, $this->key, $iv);
            $data = (string) trim(mdecrypt_generic($td, $data));
            // Make sure the encryption modules get un-loaded
            if (!mcrypt_generic_deinit($td) || !mcrypt_module_close($td)) {
                $data = false;
            }
        } else {
            $data = false;
        }
        return $data;
    }
    
    /**
     * This method will mask a credit card number
     *
     * @param String $cardNumber The card number to be masked
     * @return String The masked card number
     * @access public
     */
    function maskCardNumber($cardNumber) {
        $cardArray = str_split($cardNumber);
        $length = count($cardArray);
        $maskedCardNumber = "";
        // Mask all numbers except the last 4
        for ($i = 0; $i < $length -4; $i++) {
            $cardArray[$i] = 'X';
        }
        // Turn back into a string
        for ($i = 0; $i < $length; $i++) {
            $maskedCardNumber = $maskedCardNumber . $cardArray[$i];
        }
        return $maskedCardNumber;
    }
}
?>
