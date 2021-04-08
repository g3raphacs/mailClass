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
    private $msg = "hello world!";
    private $html = true;
    private $returnpath = "";
    private $boundary = "";

    private $MIMEversion = "MIME-Version: 1.0";

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
        if($this->hasTo()){
            $body = $this->createMessage();

            // ajout du header du message
            $headers = $this->addHeader();

            // destinataires en copie et copie cachée
            $headers .= $this->addCopyDest();

            // ajout du MIME et du content-type
            $headers .= $this->addMIME();

            // ajoute le header du body
            $body = $this->setMsgHeader($body);

            // ajoute les fichiers joints
            $body = $this->prepareFiles($body);

            // fermeture du message
            $body .= $this->closeMessage();
            
            // Envoi du mail
            if(!$this->TEST_MODE){
                return @mail(implode(",",$this->to), $this->subject, $body, $headers, $this->returnpath);  
            }
        }
        return false;   
    }
    /**
     * Teste si l'option HTML est activée
     * @return bool
     */
    private function checkHTML():bool{
        if($this->html && file_exists($this->template_file)){
            return true;
        }return false;
    }
    /**
     * Créer le message
     *
     * @param string $msg
     * @return string
     */
    private function createMessage():string{
        if($this->checkHTML()){
            // creation du message html
            $htmlContent = file_get_contents($this->template_file);
            //chercher et remplacer toutes les variables $template_data
            foreach(array_keys($this->template_data) as $key){
                if(strlen($key) > 2 && trim($key) != ""){
                    $htmlContent  =str_replace($key, $this->template_data[$key], $htmlContent);
                }
            }
            return $htmlContent;
        }
        return $this->msg;
    }

    /**
     * Valide emails
     *
     * @param array $mails
     * @return void
     */
    private function validateMails(array $mails){
        $tab = [];
        for($i=0 ; $i<count($mails) ; $i++){
            if (filter_var($mails[$i], FILTER_VALIDATE_EMAIL)) {
                $tab[$i] = $mails[$i];
            }
        }
        return $tab;
    }

    /**
     * Ajout header
     *
     * @return string
     */
    private function addHeader():string{
        $fromline = $this->fromName." <".$this->from.">";
        return "From: ".$fromline."\n";
    }

    /**
     * Fermeture du message
     *
     * @return string
     */
    private function closeMessage():string{
        return "--{$this->boundary}--";
    }

    /**
     * Ajout MIME et content-type
     *
     * @return string
     */
    private function addMIME():string{
        return "\n".$this->MIMEversion."\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$this->boundary}\"";
    }

    /**
     * Ajout destinataire en cc et bcc
     *
     * @return string
     */
    private function addCopyDest():string{
        $value="";
        if(count($this->cc)>0){
            $value = 'Cc: '.implode(",", $this->cc)."\n";
        }
        if(count($this->bcc)>0){
            $value .= 'Bcc: '.implode(",", $this->bcc);
        }
        return $value; 
    }
    /**
     * Vérifie si il y a des destinataires
     *
     * @return bool
     */
    private function hasTo():bool{
        if(count($this->to)>0){
            return true;
        }return false;
    }
    
    /**
     * Préparation des fichiers
     *
     * @param string $msg
     * @return void
     */
    private function prepareFiles($msg):string{
        $message = $msg;
        if(!empty($_FILES) && count($_FILES['file']['name'])>0 && $_FILES['file']['name'][0]!==""){
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
        if($this->checkHTML()){
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
        $this->returnpath = "-f" . $this->from;
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
        $tab = $this->validateMails($tab);
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
        $tab = $this->validateMails($tab);
        $this->cc = $tab;
        return $this;
    }
    private function set_bcc(string $bcc)
    {
        $tab = explode(",",$bcc);
        $tab = $this->validateMails($tab);
        $this->bcc = $tab;
        return $this;
    }
    private function set_msg(string $msg)
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


