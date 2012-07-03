<?php
class Codebase {

    /* Version 1.0 */

    public function __construct($username,$password,$hostname,$secure=null) {
        $this->username = $username;
        $this->password = $password;
        $this->hostname = $hostname;
        $this->secure = $secure;
        $this->url = 'http'.$this->secure.'://api3.codebasehq.com';
    }

    public function projects() {
        $xml = $this->object2array(simplexml_load_string($this->get('/projects'),'SimpleXMLElement',LIBXML_NOCDATA));
        return $xml['project'];
    }

    public function tickets($permalink) {
        $xml = $this->object2array(simplexml_load_string($this->get('/'.$permalink.'/tickets?query=sort:status'),'SimpleXMLElement',LIBXML_NOCDATA));
        return $xml['ticket'];
    }

    public function project($permalink) {
        $xml = $this->object2array(simplexml_load_string($this->get('/'.$permalink),'SimpleXMLElement',LIBXML_NOCDATA));
        return $xml;
    }

    public function notes($ticketId,$project) {
        $xml = $this->object2array(simplexml_load_string($this->get('/'.$project.'/tickets/'.$ticketId.'/notes'),'SimpleXMLElement',LIBXML_NOCDATA));
        return $xml['ticket-note'];
    }

    public function statuses($project) {
        $xml = $this->object2array(simplexml_load_string($this->get('/'.$project.'/tickets/statuses'),'SimpleXMLElement',LIBXML_NOCDATA));
        return $xml['ticketing-status'];
    }

    public function categories($project) {
        $xml = $this->object2array(simplexml_load_string($this->get('/'.$project.'/tickets/categories'),'SimpleXMLElement',LIBXML_NOCDATA));
        return $xml['ticketing-category'];
    }

    public function priorities($project) {
        $xml = $this->object2array(simplexml_load_string($this->get('/'.$project.'/tickets/priorities'),'SimpleXMLElement',LIBXML_NOCDATA));
        return $xml['ticketing-priority'];
    }

    public function addTimeEntry($project,$params) {
        $xml = '<time-session>';
        foreach($params as $key=>$value) {
            if($key=='minutes') {
                $attributes = ' type=\'integer\'';
            } elseif($key=='session-date') {
                $attributes = ' type=\'date\'';
            } else {
                $attributes = null;
            }
            $xml .= '<'.$key.$attributes.'><![CDATA['.$value.']]></'.$key.'>';
        }
        $xml .= '</time-session>';

        $result = $this->post('/'.$project.'/time_sessions',$xml);

        var_dump($result);exit;

        $result = $this->object2array(simplexml_load_string($result,'SimpleXMLElement',LIBXML_NOCDATA));
        return $result;
    }

    public function addTicket($project,$params,$files) {
        $xml = '<ticket>';
           foreach($params as $key=>$value) {
                  $xml .= '<'.$key.'><![CDATA['.$value.']]></'.$key.'>';
           }
        $xml .= '</ticket>';

        $result = $this->post('/'.$project.'/tickets',$xml);
        $result = $this->object2array(simplexml_load_string($result,'SimpleXMLElement',LIBXML_NOCDATA));
        return $result;
    }

    public function addAttachments($project,$files,$ticketId) {
        $result = null;
        foreach($files as $file) {
            $post_array['ticket_attachment[attachment]'] = '@'.$file['tmp_name'].';type='.$file['type'];
            $post_array['ticket_attachment[description]'] = $file['name'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url.'/'.$project.'/tickets/'.$ticketId.'/attachments.xml');
            curl_setopt($ch, CURLOPT_USERPWD, $this->hostname . '/'.$this->username . ':' . $this->password);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $result .= curl_exec($ch);
        }
        return $result;
    }

    public function note($project,$note,$ticketId,$changes=array()) {
        $xml = '<ticket-note>';
           $xml .= '<content><![CDATA['.$note.']]></content>';
           if(!empty($changes)) {
               $xml .= '<changes>';
                foreach($changes as $key=>$value) {
                      $xml .= '<'.$key.'><![CDATA['.$value.']]></'.$key.'>';
               }
               $xml .= '</changes>';
           }
        $xml .= '</ticket-note>';

        $result = $this->post('/'.$project.'/tickets/'.$ticketId.'/notes',$xml);
        $result = $this->object2array(simplexml_load_string($result,'SimpleXMLElement',LIBXML_NOCDATA));
        return $result;
    }

    private function request($url=null,$xml=null,$post) {
        $ch = curl_init($this->url.$url);
        if($post) {
            curl_setopt($ch, CURLOPT_POST, $post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml','Accept: application/xml'));
        curl_setopt($ch, CURLOPT_USERPWD, $this->hostname . '/'.$this->username . ':' . $this->password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        if(!$output) {
            return "fout; ".curl_error($ch);
        } else {
            return $output;
        }
        curl_close($ch);
    }

    private function post($url=null,$xml=null) {
        return $this->request($url,$xml,1);
    }

    private function get($url=null) {
        return $this->request($url,null,0);
    }

    private function object2array($object) { return @json_decode(@json_encode($object),1); }
}