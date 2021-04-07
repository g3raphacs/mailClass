<?php
/** 
 * Class MailObject 
 * Permet d'envoyer un mail
  */
class MailObject
{
    private $TEST_MODE = false;

    /**
     * 
     * Initialisation des variables
     * 
     * $from ___ $fromName Définit le mail, le nom de l'expéditeur, 
     * $to ___ Définit le(s) destinataire(s), 
     * $subject ___ Définit le sujet du mail, 
     * $template_file ___ Définit le template du formulaire html,
     * $template_data ___ Définit les variables utilisées dans le template,
     * $cc ___ Définit le(s) destinataire(s) en copie carbonne
     * $bcc ___ Définit le(s) destinataire(s) en copie cachée
     * $msg ____ Défiit le contenu du message
     * $html ____ Définit le format dans lequel sera envoyé le mail
     *
     */
    private $from ="";
    private $fromName = ""; 
    private $to = [];
    private $subject="";
    private $template_file="";
    private $template_data= array();
    private $cc = [];
    private $bcc = [];
    private $msg = "";
    private $html = true;

    // Boundary
    private $boundary;

    /**
     * Initialisation de la class
     * 
     * @param array $data 
     * @param 
     */
    function __construct(array $data = array()){
        $this->setBoundary();

        if(!empty($data)){
            $this->hydrate($data);
        }
        if(!empty($_POST)){
            $this->hydrate($_POST);
            $this->addVariables($_POST);
        }
    }
    /**
     * Mise à jour des propriétés de la classe
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
            }    
        }
    }
    /**
     * Ajout de variabes au template
     * 
     * @param array $data
     * @return void
     */
    private function addVariables(array $data):void{
        foreach ($data as $key => $value)
        {
            $newkey = "{".$key."}";
            $this->template_data[$newkey] = $value;
        }
    }

    /**
     * Boundary qui sert de séparateur de parties
     *
     * @return void
     */
    private function setBoundary():void{
        $semi_rand = md5(time());  
        $this->boundary = "==Multipart_Boundary_x{$semi_rand}x";
    }

    /**
     * Fonction pour envoyer le message
     *
     * @return boolean
     */
    public function sendMail():bool{
        $msg = $this->createMessage();

        $from = $this->fromName." <".$this->from.">";
        $headers = "From: ".$from."\n";

        // destinataires en copie et copie cachée
        $headers .= 'Cc: '.implode(",", $this->cc)."\n"; 
        $headers .= 'Bcc: '.implode(",", $this->bcc); 

        // Headers pour pieces jointes
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n";

        //ajout boundary
        $headers .= " boundary=\"{$this->boundary}\"";

        $msg = $this->setMsgHeader($msg);
        $msg = $this->prepareFiles($msg);
        $msg .= "--{$this->boundary}--"; 
        $returnpath = "-f" . $this->from;
        

        var_dump($_POST['msg']);
        // Envoi du mail
        if(!$this->TEST_MODE){
            return @mail(implode(",",$this->to), $this->subject, $msg, $headers, $returnpath);  
        }
    }

    /**
     * creer le message
     *
     * @param string $msg
     * @return string
     */
    private function createMessage():string{
        if($this->html && file_exists($this->template_file)){
            // creation du message html
            $file = $this->template_file;
            $data = $this->template_data;
            $htmlContent = file_get_contents($file);
            //chercher et remplacer toutes les variables $template_data
            foreach(array_keys($data) as $key){
                if(strlen($key) > 2 && trim($key) != ""){
                    $htmlContent  =str_replace($key, $data[$key], $htmlContent);
                }
            }
            return $htmlContent;
        }
        var_dump($this->msg);
        return $this->msg;
    }

    /**
     * Ajoute les fichiers télécharger
     *
     * @param string $msg
     * @return void
     */
    private function prepareFiles():string{
    // Preparation des fichiers
        $message = $this->msg;
        if(count($_FILES['file']['name'])>0 && $_FILES['file']['name'][0]!==""){
            for($i =0 ; $i<count($_FILES['file']['name']) ; $i++){
                $file_name = $_FILES['file']['name'][$i]; 
                $file_size = $_FILES['file']['size'][$i]; 

                $message .= "--{$this->boundary}\n"; 
                $fp =    @fopen($_FILES['file']['tmp_name'][$i], "rb"); 
                $data =  @fread($fp, $file_size); 
                @fclose($fp); 
                $data = chunk_split(base64_encode($data)); 
                $message .= "Content-Type: application/octet-stream; name=\"".$file_name."\"\n" .  
                "Content-Description: ".$file_name."\n" . 
                "Content-Disposition: attachment;\n" . " filename=\"".$file_name."\"; size=".$file_size.";\n" .  
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
            }
        }
        return $message;
    }

    /**
     * Format de message type html ou text dans le header
     *
     * @param string $msg
     * @return string
     */
    private function setMsgHeader(string $msg):string{
        if($this->html && file_exists($this->template_file)){
            $header = "Content-Type: text/html; charset=\"UTF-8\"\n"."Content-Transfer-Encoding: 7bit\n\n";
        }else{
            $header = "Content-Type: text/plain; charset=\"ISO-8859-1\"\n"."Content-Transfer-Encoding: 7bit\n\n";
        }
        return "--{$this->boundary}\n".$header.$msg."\n\n";
    }

    
    /**
     * Setters
     *
     * @param type
     */
    private function set_TEST_MODE(bool $TEST_MODE)
    {
        $this->TEST_MODE = $TEST_MODE;
        return $this;
    }

    private function set_from(string $from)
    {
        $this->from = $from;
        return $this;
    }
    private function set_fromName(string $fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }
    private function set_to(string $to)
    {
        $tab = explode(",",$to);
        $this->to = $tab;
        return $this;
    }
    private function set_subject(string $subject)
    {
        $this->subject = $subject;
        return $this;
    }
    private function set_template_file(string $template_file)
    {
        $this->template_file = $template_file;
        return $this;
    }
    private function set_template_data(array $template_data)
    {
        $this->template_data = $template_data;
        return $this;
    }
    private function set_cc(string $cc)
    {
        $tab = explode(",",$cc);
        $this->cc = $tab;
        return $this;
    }
    private function set_bcc(string $bcc)
    {
        $tab = explode(",",$bcc);
        $this->bcc = $tab;
        return $this;
    }
    private function set_msg(bool $msg)
    {
        $this->msg = $msg;
        return $this;
    }
    private function set_html(bool $html)
    {
        $this->html = $html;
        return $this;
    }
}


