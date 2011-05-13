<?php

/**
 */

/**
 * opAuthAdapterWithTwitter will handle authentication for OpenPNE by Twitter OAuth
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Mamoru Tejima <tejima@tejimaya.com>
 */
class opAuthAdapterJanrain extends opAuthAdapter
{
  protected
    $authModuleName = 'Janrain',
    $consumerKey = null,
    $consumerSecret = null,
    $urlCallback = null,
    $urlApiRoot = null,
    $urlAuthorize = null,
    $urlAuthenticate = null;

  public function configure()
  {
    $this->consumerKey = $this->getAuthConfig('awt_consumer');
    $this->consumerSecret = $this->getAuthConfig('awt_secret');
    $this->urlCallback = $this->getRequest()->getUri();
    $this->urlApiRoot = "http://api.twitter.com/";
    $this->urlAuthorize = "https://twitter.com/oauth/authorize?oauth_token=";
    $this->urlAuthenticate = "http://twitter.com/oauth/authenticate?oauth_token=";
  }


  public function authenticate()
  {
    $result = parent::authenticate();

    // Callback from Janrain
    $token    = $this->getRequest()->getParameter('token');
    if(strlen($token) == 40) {//test the length of the token; it should be 40 characters

      /* STEP 2: Use the token to make the auth_info API call */
      $post_data = array('token'  => $token,
                         'apiKey' => opConfig::get('zuniv.us.janrain_api_key',null),
                         'format' => 'json',
                         'extended' => 'true'); //Extended is not available to Basic.

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_FAILONERROR, true);
      $result = curl_exec($curl);
      if ($result == false){
        echo "\n".'Curl error: ' . curl_error($curl);
        echo "\n".'HTTP code: ' . curl_errno($curl);
        echo "\n"; var_dump($post_data);
      }
      curl_close($curl);
      /* STEP 3: Parse the JSON auth_info response */
      $auth_info = json_decode($result, true);

      if ($auth_info['stat'] == 'ok') {

        $line = Doctrine::getTable('MemberConfig')->findOneByNameAndValue("janrain_id", $auth_info["profile"]["providerName"].":::".$auth_info["profile"]["identifier"]);
      if ($line)
      {
        // 登録済み
        $member_id = (int)($line->member_id);
        $member = Doctrine::getTable('Member')->find($member_id);
      }
      else
      {
        // 新規登録
        $member = Doctrine::getTable('Member')->createPre();
        $member->setConfig("janrain_id", $auth_info["profile"]["providerName"].":::".$auth_info["profile"]["identifier"]);
        $member->setName($auth_info["profile"]["displayName"]);
      }
      $member->setIsActive(true);
      $member->save();
      $result = $member->getId();

      } else {
        echo "\n".'An error occured: ' . $auth_info['err']['msg']."\n";
        die();
      }
    }
    $uri = sfContext::getInstance()->getUser()->getAttribute('next_uri');
    if($uri)
    {
      sfContext::getInstance()->getUser()->setAttribute('next_uri', null);
      $this->getAuthForm()->setNextUri($uri);
    }
    return $result;
  }

  public function registerData($memberId, $form)
  {
    $member = Doctrine::getTable('Member')->find($memberId);
    if (!$member)
    {
      return false;
    }

    $member->setIsActive(true);
    return $member->save();
  }


  public function isRegisterBegin($member_id = null)
  {
    opActivateBehavior::disable();
    $member = Doctrine::getTable('Member')->find((int)$member_id);
    opActivateBehavior::enable();

    if (!$member || $member->getIsActive())
    {
      return false;
    }

    return true;
  }

  public function isRegisterFinish($member_id = null)
  {
    return false;
  }

}
