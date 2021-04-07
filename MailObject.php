<?php
/** 
 * Class MailObject 
 * Permet d'envoyer un mail
  */
class MailObject
{
    private $TEST_MODE = false;

    /**
     * Définit le mail, le nom de l'expéditeur, 
     * Définit le(s) destinataire(s), 
     * Définit le sujet du mail, 
     * Définit le template du formulaire html,
     * Définit les variables utilisées dans le template,
     * Définit le(s) destinataire(s) en copie carbonne
     * Définit le(s) destinataire(s) en copie cachée
     *
     * @var string
     * @var array []
     */
    private $from ="";
    private $fromName = ""; 
    private $to = [];
    private $subject="";
    private $template_file="";
    private $template_data= array();
    private $cc = [];
    private $bcc = [];

    /**
     * Initialisation de la class
     * 
     * @param array $data 
     * @param 
     */
    function __construct($data = array()){
        if(count($data)>0){
            $this->hydrate($data);
        }
    }
    /**
     * Mise à jour des propriétés de la class
     *
     * @param array $data
     * @return void
     */
    public function hydrate(array $data):void
    {
        foreach ($data as $key => $value)
        {
            $method = 'set_'.$key;
            if(method_exists($this, $method))
            {
                $this->$method($value);
                var_dump($this->$key);
            }    
        }
    }
    /**
     * Setters
     *
     * @param [type] 
     * @return void
     */
    protected function set_from($from)
    {
        $this->from = $from;
        return $this;
    }
    protected function set_fromName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }
    protected function set_to($to)
    {
        $tab = explode(",",$to);
        $this->to = $tab;
        return $this;
    }
    protected function set_subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }
    protected function set_template_file($template_file)
    {
        $this->template_file = $template_file;
        return $this;
    }
    protected function set_template_data($template_data)
    {
        $this->template_data = $template_data;
        return $this;
    }
    protected function set_cc($cc)
    {
        $tab = explode(",",$cc);
        $this->cc = $tab;
        return $this;
    }
    protected function set_bcc($bcc)
    {
        $tab = explode(",",$bcc);
        $this->bcc = $tab;
        return $this;
    }
}