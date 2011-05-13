<?php
class opAuthJanrainPluginConfigForm extends sfForm
{
  protected $configs = array(
    'zuniv.us.janrain_api_key' => 'zuniv.us.janrain_api_key',
  );
  public function configure()
  {
    $this->setWidgets(array(
      'zuniv.us.janrain_api_key' => new sfWidgetFormInput(),
    ));
    $this->setValidators(array(
      'zuniv.us.janrain_api_key' => new sfValidatorString(array(),array()),
    ));


    $this->widgetSchema->setHelp('zuniv.us.janrain_api_key', 'Janrain API Key');

    foreach($this->configs as $k => $v)
    {
      $config = Doctrine::getTable('SnsConfig')->retrieveByName($v);
      if($config)
      {
        $this->getWidgetSchema()->setDefault($k,$config->getValue());
      }
    }
    $this->getWidgetSchema()->setNameFormat('janrain[%s]');
  }
  public function save()
  {
    foreach($this->getValues() as $k => $v)
    {
      if(!isset($this->configs[$k]))
      {
        continue;
      }
      $config = Doctrine::getTable('SnsConfig')->retrieveByName($this->configs[$k]);
      if(!$config)
      {
        $config = new SnsConfig();
        $config->setName($this->configs[$k]);
      }
      $config->setValue($v);
      $config->save();
    }
  }
  public function validate($validator,$value,$arguments = array())
  {
    return $value;
  }
}
