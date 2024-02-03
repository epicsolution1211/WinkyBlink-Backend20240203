<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Common {

     /**
     * Get Song Data
     * 
     * @return Song
     */
    public function load_song($song) 
    {
        $artist = $this->artist_model->getRows(['returnType' => 'single', 'conditions' => ['id' => $song['artist_id']]]);
        if($artist) {
            $song['artist'] = $artist;
        }
        unset($song['artist_id']);

        $artist_ids = $song['artist_ids'];
        $artist_ids = explode(",", $artist_ids);
        
        $artists = array();
        foreach ($artist_ids as $artist_id) {
            $artist = $this->artist_model->getRows(['returnType' => 'single', 'conditions' => ['id' => $artist_id]]);
            if($artist) {
                array_push($artists, $artist);
            }
        }
        $song['artists'] = $artists;
        unset($song['artist_ids']);

        return $song;
    }

}