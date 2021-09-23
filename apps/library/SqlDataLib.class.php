<?php
/*
 * THis class will handle all data retrieval from DB and return an array with all data necessary
 */

require_once _PATH_ROOT_CONFIG."config_db_table.php";

class sqlDataLib{

	function sqlDataLib(){global $db,$lib; $this->db = $db; $this->lib = $lib;}

    /*
     * format data and retrieve in an array format
     */
	function fetchData($query){
	    $result=$this->db->query($query);
	   // $this->lib->debug($result);
		// exit;
	    $myArray['rows']=$this->db->nbrecord();
	  //  $this->lib->debug($myArray);
		$keywords = array(
             "''" => "'"
             ,"%" => "%25"
             ,"\n" => "<br>"
         );
	    while ($line = $this->db->fetchNextObject($result)) {
	      foreach($line as $key=>$val){
	      	$line->$key = str_replace(
	      					array_keys($keywords),
	      					array_values($keywords),
	      					utf8_encode(nl2br(stripslashes($val)))
	      					);}
	      $myArray['data'][]= $line;
	    }
		return($myArray);
	}
    /*
     * Retrieve the list of genre from DB
     */
    function getGenreList($genreID=''){
    $query= "select genre_id id, description from "._TBL_GENRE." where active='Y' ORDER by genre_id ASC";
    return ($this->fetchData($query));
  }

  function getOptionList($id,$lang='fr'){
	// echo "LANG =>$lang";
	$query= "select opt.option_id id,opt.price,opt.nb_days,  opt.description_$lang description, per.period_name_$lang period_name from "._TBL_ART_OPTION." opt, "._TBL_PERIOD." per ".
			" where  ".
			" opt.period_id = per.period_id ";
	if(trim($id)) $query.=" AND opt.option_id='$id'";
	$query.=" AND opt.active='Y' ORDER by description_$lang";
	return ($this->fetchData($query));
  }
  function getPaymentList($amount,$id='',$lang='fr'){
    // echo "LANG =>$lang";
    $query= "select payment_id id, description, img_name from "._TBL_PAYMENT_TYPE.
            " where  1 ";
    if(trim($id)) $query.=" AND payment_id='$id'";
    if($amount>0) $query.=" AND max_amount>'$amount' AND min_amount< '$amount' ";
    $query.=" AND active = 'Y' ";
    return ($this->fetchData($query));
  }


    /*
     * Retrieve the list of news from DB
     */
  function getNewsList($genreID=''){
    $query= "select news_id id, title, date_format(creation_date, '%d/%m/%Y %H:%i') date, description,url from "._TBL_NEWS.
            " where 1 ";
//      if (trim($genreID)) $query.= " genre_id='$genreID' AND ";
    $query.= " AND active='Y' ORDER by creation_date DESC";
    return ($this->fetchData($query));
  }

    /*
     * Retrieve the list of genre from DB
     */
  function getEncodingList($genreID=''){
    $query= "select rate_id id, description from "._TBL_ENCODING." where 1 ";
//      if (trim($genreID)) $query.= " genre_id='$genreID' AND ";
    $query.= " AND active='Y' ORDER by description";
    return ($this->fetchData($query));
  }

    /*
     * Retrieve the list of genre from DB
     */

  function getTopGenre($limit=''){
	$query="select sum(total) total,id, description  ".
			"from ".
			"(	".
			"	( select a.genre_id id, g.description ,count(a.genre_id) total, 'album' entry_type ".
			"	  from "._TBL_GENRE." g , "._TBL_ALBUM." a where a.genre_id=g.genre_id AND a.active='Y' group by id ".
			"	)  ".
			" UNION ".
			" 	( select so.genre_id id, g.description,count(so.genre_id) total, 'single' entry_type ".
			" 	  from "._TBL_GENRE." g , "._TBL_SINGLE." si, "._TBL_SONG." so ".
			"	 where si.song_id = so.song_id AND so.genre_id = g.genre_id AND si.active='Y' AND so.active='Y' group by id ".
			"	) ".
			") as total_genre  group by id  ";
	if(!trim($limit)) $query.=" ORDER BY total desc LIMIT 0,20";
	else $query.=" ORDER BY description ASC ";
    return ($this->fetchData($query));
  }


    /*
     * Retrieve the list of file format from DB
     */
  function getFormatList(){$query= "select file_format_id id, short_description description, long_description detail  from "._TBL_FORMAT." where active='Y' ORDER by description"; return ($this->fetchData($query));  }

    /*
     * Retrieve the list of file format from DB
     */
  function getPriceList($lang='fr'){
	$query= "select price_id id, description from "._TBL_PRICE." where 1 AND active='Y' and price_id not in( '0.00') ORDER by description ASC";
	return ($this->fetchData($query));}

  function getCopyrightCompanyList(){$query= "select company_id id, company_name description from "._TBL_COPYRIGHT_COMP." where 1 AND active='Y' ORDER by description ASC";return ($this->fetchData($query));}

  function getYearList(){$query= "select year_id id, year_id description from "._TBL_YEAR." where 1 AND active='Y' ORDER by year_id DESC";return ($this->fetchData($query));}


    /*
     * Retrieve the list of instruments from DB
     */
  function getInstrumentList(){$query= "select instrument_id, description from "._TBL_INSTRUMENT." where active='Y' ORDER by description";return ($this->fetchData($query));}
    /*
     * Retrieve the list of instruments from DB
     */
  function getInstrumentWithSingle(){ $query= "select i.instrument_id, description from "._TBL_INSTRUMENT." i, "._TBL_SINGLE_INSTRUMENT." mi where i.instrument_id = mi.instrument_id AND i.active='Y' ORDER by description";return ($this->fetchData($query));   }

    /*
     * Retrieve All artist per selected genre
     */
  function getArtistPerGenre($genreID=''){
    $query= "select art.artist_name from "._TBL_ARTIST." art, "._TBL_ALBUM." alb, "._TBL_GENRE." g  ".
            " where ".
            " art.artist_name = alb.artist_name ".
            " and alb.genre_id = g.genre_id ".
            " AND g.genre_id='$genreID' ".
            " AND art.active='Y' AND g.active='Y' AND art.approval_status='A'  AND alb.active='Y'".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )".
            " GROUP BY artist_name ".
     " UNION ".
		" select art.artist_name from "._TBL_ARTIST." art, "._TBL_SINGLE." sing, "._TBL_SONG." song , "._TBL_GENRE." g  ".
            " where ".
            " art.artist_name = sing.artist_name ".
            " and sing.song_id = song.song_id ".
            " AND song.genre_id=g.genre_id ".
            " AND g.genre_id='$genreID' ".
            " AND art.active='Y' AND g.active='Y' ".
    		" AND art.approval_status='A' AND song.active='Y' AND sing.active='Y' ".
    		" AND (song.release_date <=now() OR song.release_date IS NULL )".
            " GROUP BY artist_name ";
            //" ORDER by rand()";

			return ($this->fetchData($query));
  }

  function artistSinglePerGenre($genreID=''){
    $query= " select art.artist_name id, ".
    		" concat(art.artist_name,' (',count(sing.single_id),' ".$this->lib->txtM(123)."',')') description ".
    		" from "._TBL_ARTIST." art, "._TBL_SINGLE." sing, "._TBL_SONG." song , "._TBL_GENRE." g  ".
            " where ".
            " art.artist_name = sing.artist_name ".
            " and sing.song_id = song.song_id ".
            " AND song.genre_id=g.genre_id ".
            " AND g.genre_id='$genreID' ".
            " AND art.active='Y' AND g.active='Y' ".
    		" AND art.approval_status='A' AND song.active='Y' AND sing.active='Y'".
			" AND (song.release_date <=now() OR song.release_date IS NULL )".
            " GROUP BY sing.artist_name ";
            //" ORDER by rand()";
			return ($this->fetchData($query));
  }



  /*
     * List all album for a selected genre
     */
  function getAlbumPerGenre($genreID='',$artistID='',$limit=10){
    $query= " select album_id, album_name
            ,IF (TIME_TO_SEC(alb.album_length) >3600,
            time_format(alb.album_length, '%H:%i:%s'),
            time_format(alb.album_length, '%i:%s'))  album_length
	,art.artist_name
	,img.image_id
	,img.file_type img_type ".
//    ,( select count(sta.album_id) from "._TBL_STAT_ALBUM." sta where sta.album_id = alb.album_id ) listen
    ",alb.album_size
    ,alb.nb_songs
    ,g.description genre_description
	,alb.price_id price_value
    ,alb.genre_id
	,pr.description price_description
	,r.description rate_description
	,art.preview_length art_preview_length
	,alb.preview_length alb_preview_length
from
	"._TBL_ALBUM." alb
	,"._TBL_IMAGE." img
	,"._TBL_ARTIST." art
	,"._TBL_GENRE." g
	,"._TBL_PRICE." pr
	,"._TBL_ENCODING." r
where
	art.artist_name = alb.artist_name
    and alb.genre_id = g.genre_id
	and alb.image_id = img.image_id
    and alb.price_id = pr.price_id
    and alb.rate_id = r.rate_id
    ";
    if (trim($artistID)) $query.=" AND  alb.artist_name='$artistID' ";
    if (trim($genreID)) $query.= " AND alb.genre_id='$genreID' ";
    $query.=" AND art.active='Y' AND art.approval_status='A'  ".
    		" AND g.active ='Y' AND alb.active ='Y' AND alb.nb_songs >0 ".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )".
              " ORDER by alb.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";
    return ($this->fetchData($query));
  }

  function getLabelArtist($genreID='',$artistID='',$limit=10){
    $query= " select album_id, album_name
            ,IF (TIME_TO_SEC(alb.album_length) >3600,
            time_format(alb.album_length, '%H:%i:%s'),
            time_format(alb.album_length, '%i:%s'))  album_length
	,art.artist_name
	,img.image_id
	,img.file_type img_type ".
//    ,( select count(sta.album_id) from "._TBL_STAT_ALBUM." sta where sta.album_id = alb.album_id ) listen
    ",alb.album_size
    ,alb.nb_songs
    ,g.description genre_description
	,alb.price_id price_value
    ,alb.genre_id
	,pr.description price_description
	,r.description rate_description
	,art.preview_length art_preview_length
	,alb.preview_length alb_preview_length
from
	"._TBL_ALBUM." alb
	,"._TBL_IMAGE." img
	,"._TBL_ARTIST." art
	,"._TBL_GENRE." g
	,"._TBL_PRICE." pr
	,"._TBL_ENCODING." r
where
	art.artist_name = alb.artist_name
    and alb.genre_id = g.genre_id
	and alb.image_id = img.image_id
    and alb.price_id = pr.price_id
    and alb.rate_id = r.rate_id
    ";
    if (trim($artistID)) $query.=" AND  alb.artist_name='$artistID' ";
    if (trim($genreID)) $query.= " AND alb.genre_id='$genreID' ";
    $query.=" AND art.active='Y' AND art.approval_status='A'  ".
    		" AND g.active ='Y' AND alb.active ='Y' AND alb.nb_songs >0 ".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )".
              " ORDER by alb.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";
    return ($this->fetchData($query));
  }

  /*
     * List all album for a selected genre
     */
  function getAlbumPerDate($date=7,$limit=10){
    $query= " select album_id, album_name
            ,IF (TIME_TO_SEC(alb.album_length) >3600,
            time_format(alb.album_length, '%H:%i:%s'),
            time_format(alb.album_length, '%i:%s'))  album_length
	,art.artist_name
    ,date_format(alb.creation_date, '%d/%m/%Y')  date
	,img.image_id
	,img.file_type img_type ".
//    ,( select count(sta.album_id) from "._TBL_STAT_ALBUM." sta where sta.album_id = alb.album_id ) listen
    ",alb.album_size
    ,alb.nb_songs
    ,g.description genre_description
	,alb.price_id price_value
    ,alb.genre_id
	,pr.description price_description
	,r.description rate_description
	,art.preview_length art_preview_length
	,alb.preview_length alb_preview_length
from
	"._TBL_ALBUM." alb
	,"._TBL_IMAGE." img
	,"._TBL_ARTIST." art
	,"._TBL_GENRE." g
	,"._TBL_PRICE." pr
	,"._TBL_ENCODING." r
where
	art.artist_name = alb.artist_name
    and alb.genre_id = g.genre_id
	and alb.image_id = img.image_id
    and alb.price_id = pr.price_id
    and alb.rate_id = r.rate_id
    ";
//    if (trim($artistID)) $query.=" AND  alb.artist_name='$artistID' ";
//    if (trim($genreID)) $query.= " AND alb.genre_id='$genreID' ";
    if (trim($date)) $query.= " AND alb.creation_date between DATE_SUB(CURDATE(),INTERVAL $date day) and CURDATE()  ";
    $query.=" AND art.active='Y' AND art.approval_status='A'  ".
    		"AND g.active ='Y' AND alb.active ='Y' AND alb.nb_songs >0 ".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )".
              " ORDER by alb.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";
    return ($this->fetchData($query));
  }


// Free album
  function getFreeAlbum($genreID='',$artistID='',$limit=50){
    $query= " select album_id, album_name
        ,alb.album_length length
	,art.artist_name
	,img.image_id
	,img.file_type img_type
    ,( select count(sta.album_id) from "._TBL_STAT_ALBUM." sta where sta.album_id = alb.album_id ) listen
    ,alb.album_size
    ,alb.nb_songs
    ,g.description genre_description
	,alb.price_id price_value
	,pr.description price_description
	,r.description rate_description
	,art.preview_length art_preview_length
	,alb.preview_length alb_preview_length
from
	"._TBL_ALBUM." alb
	,"._TBL_IMAGE." img
	,"._TBL_ARTIST." art
	,"._TBL_GENRE." g
	,"._TBL_PRICE." pr
	,"._TBL_ENCODING." r
where
	art.artist_name = alb.artist_name
    and alb.genre_id = g.genre_id
	and alb.image_id = img.image_id
    and alb.price_id = pr.price_id
    and alb.rate_id = r.rate_id
    ";
    if (trim($artistID)) $query.=" AND  alb.artist_name='$artistID' ";
    if (trim($genreID)) $query.= " AND alb.genre_id='$genreID' ";
    $query.=" AND alb.price_id='0.00' AND art.active='Y' AND art.approval_status='A'  AND g.active ='Y' AND alb.active ='Y'".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )".
              " ORDER by alb.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";
    return ($this->fetchData($query));
  }

  function getAlbumAllPerArtist($artistID='',$excludeID='',$limit=20){
    $query= " select album_id, album_name ".
//     " ,( select  IF(SUM(TIME_TO_SEC(length ))>3600, time_format(SEC_TO_TIME(SUM(TIME_TO_SEC(length))), '%H:%i:%s'), time_format(SEC_TO_TIME(SUM(TIME_TO_SEC(length))), '%i:%s'))
  //      from "._TBL_ALBUM_SONG." sa, song_files f where f.song_id = sa.song_id AND sa.album_id =alb.album_id and f.active='Y')  "
    " ,IF (TIME_TO_SEC(alb.album_length) >3600,
            time_format(alb.album_length, '%H:%i:%s'),
            time_format(alb.album_length, '%i:%s'))  album_length
	,art.artist_name
	,img.image_id
	,img.file_type img_type ".
//    ,( select count(sta.album_id) from "._TBL_STAT_ALBUM." sta where sta.album_id = alb.album_id ) listen ".
//     ,(        select sum(f.filesize)  from "._TBL_ALBUM_SONG." sa, song_files f where sa.song_id = f.song_id and sa.album_id = alb.album_id and f.active='Y'    ) AS
    ",alb.album_size ".
//    ,(  select count(sa.song_id)  from "._TBL_ALBUM_SONG." sa, song_files f   where sa.album_id = alb.album_id and sa.song_id = f.song_id and f.active='Y'    ) AS
    " ,alb.nb_songs
    ,g.description genre_description
    ,alb.genre_id
	,alb.price_id price_value
	,pr.description price_description
	,r.description rate_description
	,art.preview_length art_preview_length
	,alb.preview_length alb_preview_length
from
	"._TBL_ALBUM." alb
	,"._TBL_IMAGE." img
	,"._TBL_ARTIST." art
	,"._TBL_GENRE." g
	,"._TBL_PRICE." pr
	,"._TBL_ENCODING." r
where
	art.artist_name = alb.artist_name
    and alb.genre_id = g.genre_id
	and alb.image_id = img.image_id
    and alb.price_id = pr.price_id
    and alb.rate_id = r.rate_id
    ";
    if (trim($artistID)) $query.=" AND  alb.artist_name='$artistID' ";
    if (trim($excludeID)) $query.= " AND alb.album_id not in('$excludeID') ";
    $query.=" AND art.active='Y' AND art.approval_status='A'  AND g.active ='Y' AND alb.active ='Y'".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )".
              " ORDER by alb.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";
    return ($this->fetchData($query));
  }

  function getAlbumPerGenreExcludeArtist($genreID='',$artistID='',$limit=10){
    $query= " select album_id, album_name
        ,IF (TIME_TO_SEC(alb.album_length) >3600,
            time_format(alb.album_length, '%H:%i:%s'),
            time_format(alb.album_length, '%i:%s'))  album_length
	,art.artist_name
	,img.image_id
	,img.file_type img_type ".
//    ,( select count(sta.album_id) from "._TBL_STAT_ALBUM." sta where sta.album_id = alb.album_id ) listen
  "  ,alb.album_size
    ,alb.nb_songs
    ,g.description genre_description
    ,alb.genre_id
	,alb.price_id price_value
	,pr.description price_description
	,r.description rate_description
	,art.preview_length art_preview_length
	,alb.preview_length alb_preview_length
from
	"._TBL_ALBUM." alb
	,"._TBL_IMAGE." img
	,"._TBL_ARTIST." art
	,"._TBL_GENRE." g
	,"._TBL_PRICE." pr
	,"._TBL_ENCODING." r
where
	art.artist_name = alb.artist_name
    and alb.genre_id = g.genre_id
	and alb.image_id = img.image_id
    and alb.price_id = pr.price_id
    and alb.rate_id = r.rate_id
    ";
    if (trim($artistID)) $query.=" AND  alb.artist_name not in('$artistID') ";
    if (trim($genreID)) $query.= " AND alb.genre_id='$genreID' ";
    $query.=" AND art.active='Y' AND art.approval_status='A'  AND g.active ='Y' AND alb.active ='Y'".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )".
              " ORDER by alb.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";
    return ($this->fetchData($query));
  }


  function getExclusiveAlbums($genreID='',$artistID=''){
    $query= "select album_id, album_name, alb.album_length length,art.artist_name, alb.description,alb.image_id, alb.img_type, alb.nb_download ".
            ", alb.nb_songs, alb.genre_id , gen.description genre_description, price_id price_value, price_description,alb.rate_description ".
            " from album_view alb ".
            ", "._TBL_ARTIST." art ".
            ", "._TBL_GENRE." gen ".
            " WHERE ".
            " alb.artist_name = art.artist_name ".
            " AND alb.genre_id = gen.genre_id";
    if (trim($artistID)) $query.=" AND  alb.artist_name='$artistID' ";
    if (trim($genreID)) $query.= " AND alb.genre_id='$genreID' ";
    $query.=" AND art.active='Y' AND ( art.exclusivity_flag='Y' or early_artist_flag='Y') AND art.approval_status='A'  AND gen.active ='Y' AND alb.active ='Y'".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )".
              " ORDER by alb.creation_date DESC ";
    return ($this->fetchData($query));
  }

  function getArtistAlbums($pseudo=''){
    $query= " select album_id, album_name
            ,IF (TIME_TO_SEC(alb.album_length) >3600,
            time_format(alb.album_length, '%H:%i:%s'),
            time_format(alb.album_length, '%i:%s'))  album_length
	,art.artist_name
    ,(
        select count(sa.song_id) from "._TBL_ALBUM_SONG." sa, song_files f where sa.album_id = alb.album_id and sa.song_id = f.song_id and f.active='Y'
    ) AS nb_songs
    ,alb.genre_id
	,alb.price_id price_value
	,alb.active
from
	"._TBL_ALBUM." alb
	,"._TBL_ARTIST." art
where
	art.artist_name = alb.artist_name
    AND art.pseudo='$pseudo'
    AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )
    ";
    return ($this->fetchData($query));
  }

  function getArtistSingle($pseudo,$artist=''){
    $query= "select sing.single_id,song.song_id, song.title, song.rate_id, time_format(song.length, '%i:%s') length, rate.description rate_desc  ".
            " ,sing.nb_download,art.artist_name, sing.image_id, img.file_type ".
            " ,gen.genre_id , gen.description genre_description, pr.price_id price_value, pr.description price_description,sing.active ".
            " from ".
            _TBL_SINGLE." sing, ".
            _TBL_ARTIST." art, ".
            _TBL_SONG." song, ".
            _TBL_GENRE." gen,  ".
            _TBL_IMAGE." img,  ".
            _TBL_ENCODING." rate,  ".
            _TBL_PRICE." pr ".
            " where   ".
            " art.artist_name = sing.artist_name   ".
            " AND sing.image_id = img.image_id ".
            " AND sing.song_id = song.song_id ".
            " AND song.genre_id = gen.genre_id ".
            " and pr.price_id = sing.price_id ".
            " AND rate.rate_id = song.rate_id ".
            " AND art.pseudo='$pseudo'  AND song.active='Y' ".
            " AND (song.release_date <=now() OR song.release_date IS NULL )";
            //" AND art.artist_name='$artist'";
    return ($this->fetchData($query));
  }

  function getArtistSongFile($pseudo=''){
    $query= "select * from "._TBL_SONG." f  where  1 AND f.pseudo='$pseudo' ORDER BY title ASC "; return ($this->fetchData($query));
  }
  function getArtistSongPreviewFile($pseudo=''){
    $query= "select *,preview_id id from "._TBL_SONG_PREVIEW." where pseudo='$pseudo' ORDER BY title ASC "; return ($this->fetchData($query));
  }

  function getArtistSongPreviewFileForm($pseudo='',$form=''){
    $query='';
    if($form=='Y') $query.="select ''";
    $query= "select *,preview_id id from "._TBL_SONG_PREVIEW." where pseudo='$pseudo' ORDER BY title ASC "; return ($this->fetchData($query));
  }

  function getArtistVideoFile($pseudo=''){
    $query= "select * from "._TBL_VIDEO." f  where  1 AND f.pseudo='$pseudo' ORDER BY creation_date DESC "; return ($this->fetchData($query));
  }

  function getPseudoEvent($pseudo=''){
    $query= "select event_id id,title, date_format(start_date,'%d/%m/%Y') from_date ,date_format(end_date,'%d/%m/%Y') to_date, e.active, c.description country".
            " from "._TBL_EVENT." e, "._TBL_COUNTRY." c where  e.country_id = c.country_id AND e.pseudo='$pseudo' AND e.active ='Y'";
    return ($this->fetchData($query));
  }

	function getAllEvent($period='',$pseudo='',$limit=50,$artist=''){
		if(!trim($period) || $period<1) $period=7;
		$date=" e.end_date <= DATE_ADD(CURDATE(),INTERVAL ".$period." DAY)";
		$query="select c.description country,e.title, e.event_id, e.image_id,e.pseudo, date_format(e.start_date,'%d-%m-%Y') start_date, date_format(e.end_date,'%d-%m-%Y') end_date ".
		        ", (select file_name from "._TBL_IMAGE." I where e.image_id = I.image_id) image_name ".
				" , (select file_type from "._TBL_IMAGE." I where e.image_id = I.image_id) img_type ".

			" from "._TBL_EVENT." e,"._TBL_SUBSCRIBER." s, "._TBL_COUNTRY." c ".
      " where e.country_id=c.country_id AND e.pseudo = s.pseudo and $date AND e.end_date>=CURDATE()";
		if(trim($pseudo)) $query.=" and e.pseudo = '$pseudo'";
    if(trim($artist)) $query.=" and e.artist_name ='$artist'";
		$query.=" ORDER BY e.start_date ASC ";
        if($limit>0) $query.=" LIMIT 0,$limit ";
		return ($this->fetchData($query));
	}

	function getEventForPlayer($artist='',$pseudo=''){
		//echo " artist => $artist, pseudo=>$pseudo";

		$period=90;
		$date=" e.end_date <= DATE_ADD(CURDATE(),INTERVAL ".$period." DAY)";
		if(trim($artist)){
		$query="select e.title, e.event_id id, date_format(e.start_date,'%d-%m-%Y') start_date, date_format(e.end_date,'%d-%m-%Y') end_date ".
				", (select CONCAT('"._SITE."/imgView/',e.image_id,'/',I.file_type,'/medium') ".
				" from "._TBL_IMAGE." I where e.image_id = I.image_id) image_url ".
				" ,e.price, c.description country".
				" ,CONCAT('"._SITE."/eventDetail/',e.event_id) visit_url ".
			" from "._TBL_EVENT." e,"._TBL_ARTIST." a,"._TBL_COUNTRY." c where e.pseudo = a.pseudo AND e.country_id=c.country_id ".
			" and a.artist_name='$artist' AND  e.end_date>=CURDATE()";
		//if(trim($pseudo)) $query.=" and e.pseudo = '$pseudo'";
		}
		if(trim($pseudo)){
		//	echo "<br>$pseudo";
		$query="select e.title, e.event_id id, date_format(e.start_date,'%d-%m-%Y') start_date, date_format(e.end_date,'%d-%m-%Y') end_date ".
				", (select CONCAT('"._SITE."/imgView/',e.image_id,'/',I.file_type,'/medium') ".
				" from "._TBL_IMAGE." I where e.image_id = I.image_id) image_url ".
				" ,e.price, c.description country".
				" ,CONCAT('"._SITE."/eventDetail/',e.event_id) visit_url ".
			" from "._TBL_EVENT." e,"._TBL_SUBSCRIBER." s,"._TBL_COUNTRY." c where e.pseudo = s.pseudo  and e.pseudo = '$pseudo' AND e.country_id=c.country_id ".
			" and  $date AND  e.end_date>=CURDATE() ";
		}

		$query.="ORDER BY e.start_date ASC";
//		echo "<br>fin query=<br>".$query;
		return ($this->fetchData($query));
	}

  function retrieveEventDetail($evtID='',$pseudo=''){
    if(trim($evtID)){
		$query= "select e.*,date_format(e.start_date, '%d/%m/%Y') from_date,date_format(e.end_date, '%d/%m/%Y') to_date,c.description country_desc ".
				", (select CONCAT(I.image_id,'/',I.file_type) from "._TBL_IMAGE." I where I.image_id = e.image_id ) imageID ".
              " from "._TBL_EVENT." e,"._TBL_COUNTRY." c ".
              " where  1  AND e.country_id = c.country_id ";
		if(trim($pseudo)) $query.= " AND e.pseudo='$pseudo'";
        $query.= " AND e.event_id='$evtID'".
              " AND e.active ='Y'";
      return ($this->fetchData($query));
    }
  }

  function getArtistImage1($artistID=''){
    $query= "select f.image_id, f.description, f.file_name,f.file_size, f.image_type img_type from "._TBL_IMAGE." f where  1 AND f.pseudo='$artistID' ORDER BY creation_date DESC"; return ($this->fetchData($query));
  }

  function getArtistOption($artistID=''){
    $query= "select opt.description, date_format(ord.from_date, '%d/%m/%Y') from_date, ".
            " date_format(ord.payment_date, '%d/%m/%Y') payment_date, ".
            " date_format(ord.to_date, '%d/%m/%Y') to_date, ".
            " date_format(ord.order_date, '%d/%m/%Y') order_date, ".
            " opt.nb_days, ord.payed, opt.price ".
            " from ".
            _TBL_ART_OPTION." opt ".
            ","._TBL_ART_ORDER." ord ".
            " where  1 ".
            " AND ord.pseudo='$artistID'".
            " AND opt.option_code=ord.option_code ";
    return ($this->fetchData($query));
  }

  function getArtistFileForm($artistID='', $fileFormatID=''){
    $query= "select f.file_id id, f.description from "._TBL_FILE." f where  1 AND f.pseudo='$artistID'"; if(trim($fileFormatID)) $query.=" AND file_format_id='$fileFormatID'";
    return ($this->fetchData($query));
  }

  function getArtistImage($pseudo=''){ $query= "select f.image_id id, f.description, f.file_name, f.file_size, f.file_type img_type from "._TBL_IMAGE." f where f.pseudo='$pseudo'"; return ($this->fetchData($query));}
  function getArtistSong($pseudo=''){ $query= "select f.song_id id, f.title description from "._TBL_SONG." f where f.pseudo='$pseudo' and f.active='Y'"; return ($this->fetchData($query));}
  function getArtistSongPreview($pseudo=''){ $query= "select '' id, '".$this->lib->txtM(98)."' description from dual UNION select preview_id id, title description from "._TBL_SONG_PREVIEW." where pseudo='$pseudo' and active='Y'"; return ($this->fetchData($query));}
  function getArtistVideo($pseudo=''){ $query= "select v.video_id id, v.title description from "._TBL_VIDEO." v where v.pseudo='$pseudo' and v.active='Y'"; return ($this->fetchData($query));}


   /*
     * List album detail
     */
  function getAlbumDetail($albumID=''){
    $query= " select album_id, album_name,alb.description
            ,IF (TIME_TO_SEC(alb.album_length) >3600,
            time_format(alb.album_length, '%H:%i:%s'),
            time_format(alb.album_length, '%i:%s'))  album_length
	,art.artist_name
    , alb.year_id
	,img.image_id
	,img.file_type img_type ".
//    ,( select count(sta.album_id) from "._TBL_STAT_ALBUM." sta where sta.album_id = alb.album_id ) listen ".
//    ,(select sum(f.filesize)  from "._TBL_ALBUM_SONG." sa, song_files f where sa.song_id = f.song_id and sa.album_id = alb.album_id and f.active='Y'    ) AS
    " ,alb.album_size ".
//     ,(select count(sa.song_id) from "._TBL_ALBUM_SONG." sa, song_files fwhere sa.album_id = alb.album_id and sa.song_id = f.song_id and f.active='Y'    ) AS
    " , alb.nb_songs
    ,g.description genre_description
	,alb.price_id price_value
    ,alb.genre_id
	,pr.description price_description
	,r.description rate_description
	,art.preview_length art_preview_length
	,alb.preview_length alb_preview_length
from
	"._TBL_ALBUM." alb
	,"._TBL_IMAGE." img
	,"._TBL_ARTIST." art
	,"._TBL_GENRE." g
	,"._TBL_PRICE." pr
	,"._TBL_ENCODING." r
where
	art.artist_name = alb.artist_name
    and alb.genre_id = g.genre_id
	and alb.image_id = img.image_id
    and alb.price_id = pr.price_id
    and alb.rate_id = r.rate_id
    AND alb.album_id='$albumID'
    AND alb.nb_songs > 0
    AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )
     AND art.active='Y' AND art.approval_status='A'  AND alb.active ='Y'";

    return ($this->fetchData($query));
  }

  function getCartAlbumDetail($albumID=''){
    $query= " select alb.artist_name,alb.album_id, alb.image_id, img.file_type img_type,
            alb.album_name
            ,( select count(sa.song_id) from "._TBL_ALBUM_SONG." sa, song_files f  where sa.album_id = alb.album_id and sa.song_id = f.song_id and f.active='Y'
    ) AS nb_songs
    , art.artist_name  ,pr.price_id price_value, pr.description price_description,r.description rate_description
    ,g.description genre
	,CONCAT('"._SITE."/genre/',g.genre_id) genre_url
from
	"._TBL_ALBUM." alb
	,"._TBL_IMAGE." img
	,"._TBL_ARTIST." art
	,"._TBL_GENRE." g
	,"._TBL_PRICE." pr
	,"._TBL_ENCODING." r
where
	art.artist_name = alb.artist_name
    and alb.genre_id = g.genre_id
	and alb.image_id = img.image_id
    and alb.price_id = pr.price_id
    and alb.rate_id = r.rate_id
    AND alb.album_id='$albumID'
     AND art.active='Y' AND art.approval_status='A'  AND alb.active ='Y'".
    " AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )";
    return ($this->fetchData($query));
  }


  /*
   * List album detail
   */
  function getSingleDetail($singleID=''){
    $query= "select sing.single_id , song.title,sing.description, time_format(song.length, '%i:%s') length, r.description rate_description".
            " , g.description genre_description ,g.genre_id, sing.image_id, img.file_type img_type ".
            ",  sing.nb_download, art.artist_name,year_id ".
            ", pr.price_id price_value, pr.description price_description  ".
			", art.preview_length art_preview_length, sing.preview_length sing_preview_length, date_format(song.release_date, '%D/%M/%Y') release_date ".
            " from ".
            _TBL_SINGLE." sing, ".
            _TBL_ARTIST." art, ".
            _TBL_SONG." song, ".
            _TBL_IMAGE." img, ".
			_TBL_GENRE." g ,".
            _TBL_ENCODING." r, ".
            _TBL_PRICE." pr ".
            " where ".
            " sing.artist_name = art.artist_name ".
            " AND sing.price_id = pr.price_id ".
            " AND sing.image_id = img.image_id ".
            " AND sing.song_id = song.song_id ".
			" AND song.rate_id = r.rate_id ".
			" AND song.genre_id = g.genre_id ".
            " AND sing.single_id='$singleID' ".
            " AND art.active='Y' AND art.approval_status='A' AND sing.active ='Y' AND song.active='Y' ".
            " AND (song.release_date <=now() OR song.release_date IS NULL )";
    return ($this->fetchData($query));
  }

  /*
   * list all single for a given artist
   */
  function getSinglePerArtist($artist,$genreID,$encodingID=''){
    if(trim($artist)){
      $query= "SELECT sing.single_id id , CONCAT(song.title, ' ( ',time_format(song.length, '%i:%s'),' mins )') description ".
              "FROM "._TBL_SINGLE." sing , "._TBL_SONG." song where sing.song_id=song.song_id  AND sing.artist_name='$artist' ";
      if (trim($genreID)) $query.= "and song.genre_id='$genreID' ";
      if (trim($encodingID)) $query.= "and song.rate_id='$encodingID' ";
      $query.="AND sing.active='Y'  AND song.active='Y' ".
      " AND (song.release_date <=now() OR song.release_date IS NULL ) ORDER by sing.description";
      return ($this->fetchData($query));
    }
  }


  function getMp3PerArtist($artist,$genreID,$encodingID){
    if(trim($artist) && trim($encodingID)){
      $query= "SELECT song.song_id id , CONCAT(song.title, ' ( ',time_format(song.length, '%i:%s'),' mins )') description ".
              "FROM "._TBL_SONG." song where song.pseudo='$artist' ";
//       if (trim($genreID)) $query.= "and sing.genre_id='$genreID' ";
      if (trim($encodingID)) $query.= "and song.rate_id='$encodingID' ";
      $query.="AND song.active='Y' ORDER by song.title ";
      return ($this->fetchData($query));
    }
  }

  function getUnpublishedMp3PerArtist($pseudo,$artist){
    if(trim($pseudo) && trim($artist)){
      $query= "SELECT song.song_id id , CONCAT(song.title, ' ( ',time_format(song.length, '%i:%s'),' mins )') description ".
              "FROM "._TBL_SONG." song where song.pseudo='$pseudo' AND artist_name ='".addslashes($artist)."' and song_id NOT IN (select song_id from "._TBL_SINGLE." where artist_name ='$artist')";
//       if (trim($genreID)) $query.= "and sing.genre_id='$genreID' ";
      if (trim($encodingID)) $query.= "and song.rate_id='$encodingID' ";
      $query.="AND song.active='Y' ORDER by song.title ";
      return ($this->fetchData($query));
    }
  }

  function getMp3PerArtistFormList($pseudo,$rate){
	$query= "SELECT song.song_id id , CONCAT(song.title, ' ( ',time_format(song.length, '%i:%s'),' mins )') description ".
              "FROM "._TBL_SONG." song where song.pseudo='$pseudo' AND song.rate_id='$rate' ";
    $query.="AND song.active='Y' ORDER by song.title ASC, song.lengthASC ";
	return ($this->fetchData($query));
  }



  /*
   * List all single for a selected genre
   */
  function getSinglePerGenre($genreID='',$artist='',$limit=10){
    $query= "SELECT sing.single_id,song.song_id,art.artist_name,song.title description, sing.image_id ".
         ", sing.image_id, img.file_type img_type".
         ", sing.nb_download , gen.genre_id , gen.description genre_description ".
         ", pr.price_id price_value, pr.description price_description, rate.description rate_description ".
         " ,time_format(song.length, '%i:%s') length ".
		 ", art.preview_length art_preview_length, sing.preview_length sing_preview_length ".
            "FROM ".
//              _TBL_ALBUM." alb, ".
            _TBL_ARTIST." art ".
            ", "._TBL_SINGLE." sing ".
            ", "._TBL_GENRE." gen  ".
            ", "._TBL_PRICE." pr ".
            ", "._TBL_SONG." song ".
            ", "._TBL_IMAGE." img ".
            ", "._TBL_ENCODING." rate ".
            " where ".
            " sing.song_id = song.song_id ".
            " AND sing.image_id=img.image_id  ".
            " AND song.genre_id=gen.genre_id  ".
            " and song.rate_id = rate.rate_id ".
            " and pr.price_id = sing.price_id ".
            " and pr.price_id = sing.price_id ".
            " AND art.artist_name = sing.artist_name ";
    if (trim($artist)) $query.=" AND art.artist_name='$artist' ";
    if (trim($genreID)) $query.= "and gen.genre_id='$genreID' ";
    $query.=" AND art.active='Y' AND art.approval_status='A'  AND song.active='Y' AND sing.active='Y' ".
    		" AND gen.active ='Y' ".
    		" AND (song.release_date <=now() OR song.release_date IS NULL ) ".
    		" ORDER BY sing.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";

    return ($this->fetchData($query));
  }

  // Single per date
    function getSinglePerDate($date=7,$limit=10){
    $query= "SELECT sing.single_id,song.song_id,art.artist_name,song.title description, sing.image_id ".
         ", sing.image_id, img.file_type img_type".
         ", sing.nb_download , gen.genre_id , gen.description genre_description ".
         ", pr.price_id price_value, pr.description price_description, rate.description rate_description ".
         " ,time_format(song.length, '%i:%s') length ".
		 ", art.preview_length art_preview_length, sing.preview_length sing_preview_length ".
         ", date_format(sing.creation_date,'%d/%m/%Y') date ".
         "FROM ".
//              _TBL_ALBUM." alb, ".
            _TBL_ARTIST." art ".
            ", "._TBL_SINGLE." sing ".
            ", "._TBL_GENRE." gen  ".
            ", "._TBL_PRICE." pr ".
            ", "._TBL_SONG." song ".
            ", "._TBL_IMAGE." img ".
            ", "._TBL_ENCODING." rate ".
            " where ".
            " sing.song_id = song.song_id ".
            " AND sing.image_id=img.image_id  ".
            " AND song.genre_id=gen.genre_id  ".
            " and song.rate_id = rate.rate_id ".
            " and pr.price_id = sing.price_id ".
            " and pr.price_id = sing.price_id ".
            " AND art.artist_name = sing.artist_name ";
//    if (trim($artist)) $query.=" AND art.artist_name='$artist' ";
  //  if (trim($genreID)) $query.= "and gen.genre_id='$genreID' ";
    if (trim($date)) $query.= " AND sing.creation_date between DATE_SUB(CURDATE(),INTERVAL $date day) and CURDATE()  ";
    $query.=" AND art.active='Y' AND art.approval_status='A'  AND song.active='Y' ".
    		"AND sing.active='Y' AND gen.active ='Y' ".
    		" AND (song.release_date <=now() OR song.release_date IS NULL ) ".
    		" ORDER BY sing.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";

    return ($this->fetchData($query));
  }

 // Free singles
  function getFreeSingle($genreID='',$artist='',$limit=50){
    $query= "SELECT sing.single_id,song.song_id,art.artist_name,song.title description, sing.image_id ".
         ", sing.image_id, img.file_type img_type".
         ", sing.nb_download , gen.genre_id , gen.description genre_description ".
         ", pr.price_id price_value, pr.description price_description, rate.description rate_description ".
         " ,time_format(song.length, '%i:%s') length ".
		 ", art.preview_length art_preview_length, sing.preview_length sing_preview_length ".
            "FROM ".
//              _TBL_ALBUM." alb, ".
            _TBL_ARTIST." art ".
            ", "._TBL_SINGLE." sing ".
            ", "._TBL_GENRE." gen  ".
            ", "._TBL_PRICE." pr ".
            ", "._TBL_SONG." song ".
            ", "._TBL_IMAGE." img ".
            ", "._TBL_ENCODING." rate ".
            " where ".
            " sing.song_id = song.song_id ".
            " AND sing.image_id=img.image_id  ".
            " AND song.genre_id=gen.genre_id  ".
            " and song.rate_id = rate.rate_id ".
            " and pr.price_id = sing.price_id ".
            " and pr.price_id = sing.price_id ".
            " AND art.artist_name = sing.artist_name ";
    if (trim($artist)) $query.=" AND art.artist_name='$artist' ";
    if (trim($genreID)) $query.= "and gen.genre_id='$genreID' ";
    $query.=" AND sing.price_id='0.00' AND art.active='Y' AND art.approval_status='A'  ".
    		" AND song.active='Y' AND sing.active='Y' AND gen.active ='Y' ".
    		" AND (song.release_date <=now() OR song.release_date IS NULL ) ".
    		" ORDER BY sing.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";

    return ($this->fetchData($query));
  }

  function getSinglePerGenreExcludeArtist($genreID='',$artist='',$limit=20){
    $query= "SELECT sing.single_id,song.song_id,art.artist_name,song.title description, sing.image_id ".
         ", sing.image_id, img.file_type img_type".
         ", sing.nb_download , gen.genre_id , gen.description genre_description ".
         ", pr.price_id price_value, pr.description price_description, rate.description rate_description ".
         " ,time_format(song.length, '%i:%s') length ".
		 ", art.preview_length art_preview_length, sing.preview_length sing_preview_length ".
            "FROM ".
//              _TBL_ALBUM." alb, ".
            _TBL_ARTIST." art ".
            ", "._TBL_SINGLE." sing ".
            ", "._TBL_GENRE." gen  ".
            ", "._TBL_PRICE." pr ".
            ", "._TBL_SONG." song ".
            ", "._TBL_IMAGE." img ".
            ", "._TBL_ENCODING." rate ".
            " where ".
            " sing.song_id = song.song_id ".
            " AND sing.image_id=img.image_id  ".
            " AND song.genre_id=gen.genre_id  ".
            " and song.rate_id = rate.rate_id ".
            " and pr.price_id = sing.price_id ".
            " and pr.price_id = sing.price_id ".
            " AND art.artist_name = sing.artist_name ";
    if (trim($artist)) $query.=" AND art.artist_name not in ('$artist') ";
    if (trim($genreID)) $query.= "and gen.genre_id='$genreID' ";
    $query.=" AND song.active='Y' and art.active='Y' AND art.approval_status='A' ".
    		" AND sing.active='Y' AND gen.active ='Y' ".
    		" AND (song.release_date <=now() OR song.release_date IS NULL ) ".
    		" ORDER BY sing.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";

    return ($this->fetchData($query));
  }

  function getSingleAllPerArtist($artist='',$excludeSingleID='',$limit=25){
    $query= "SELECT sing.single_id,song.song_id,art.artist_name,song.title description, sing.image_id ".
         ", sing.image_id, img.file_type img_type".
         ", sing.nb_download , gen.genre_id , gen.description genre_description ".
         ", pr.price_id price_value, pr.description price_description, rate.description rate_description ".
         " ,time_format(song.length, '%i:%s') length ".
		 ", art.preview_length art_preview_length, sing.preview_length sing_preview_length ".
            "FROM ".
//              _TBL_ALBUM." alb, ".
            _TBL_ARTIST." art ".
            ", "._TBL_SINGLE." sing ".
            ", "._TBL_GENRE." gen  ".
            ", "._TBL_PRICE." pr ".
            ", "._TBL_SONG." song ".
            ", "._TBL_IMAGE." img ".
            ", "._TBL_ENCODING." rate ".
            " where ".
            " sing.song_id = song.song_id ".
            " AND sing.image_id=img.image_id  ".
            " AND song.genre_id=gen.genre_id  ".
            " and song.rate_id = rate.rate_id ".
            " and pr.price_id = sing.price_id ".
            " and pr.price_id = sing.price_id ".
            " AND art.artist_name = sing.artist_name ";
    if (trim($artist)) $query.=" AND art.artist_name='$artist' ";
    if (trim($excludeSingleID)) $query.= "and sing.single_id not in ('$excludeSingleID') ";
    $query.=" AND song.active='Y' AND art.active='Y' AND art.approval_status='A' ".
    		" AND sing.active='Y' AND gen.active ='Y' ".
    		" AND (song.release_date <=now() OR song.release_date IS NULL ) ".
    		" ORDER BY sing.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";

    return ($this->fetchData($query));
  }

  /* Exclusive albums */
  function getExclusiveAlbumsForPlayer($genreID='',$artistID=''){
    $query= "select album_id id ".
			",alb.album_name title ".
    		", CONCAT('"._SITE."/shoppingCart/add/album/',alb.album_id,'/PL/Y') visit_url ".
			",CONCAT('"._SITE."/imgView/',alb.image_id,'/',alb.img_type,'/medium') image_url ".
			",CONCAT('"._SITE."/pXml/sngList/',album_id) songs ".
			", alb.album_length length ".
			",art.artist_name artist".
			",CONCAT('"._SITE."/artist/',art.artist_name) artist_url ".
            //", alb.nb_songs ".
			", gen.description genre ".
			//",alb.rate_description ".
			", price_description price ".
            " from album_view alb ".
            ", "._TBL_ARTIST." art ".
            ", "._TBL_GENRE." gen ".
            " WHERE ".
            " alb.artist_name = art.artist_name ".
            " AND alb.genre_id = gen.genre_id";
    if (trim($artistID)) $query.=" AND  alb.artist_name='$artistID' ";
    if (trim($genreID)) $query.= " AND alb.genre_id='$genreID' ";
    $query.=" AND art.active='Y' AND ( art.exclusivity_flag='Y' or early_artist_flag='Y') ".
    		" AND art.approval_status='A'  AND gen.active ='Y' AND alb.active ='Y'".
    		" AND (song.release_date <=now() OR song.release_date IS NULL ) ".
              " ORDER by alb.creation_date DESC ";
    return ($this->fetchData($query));
  }

	function getPlayListSingleItemsForPlayer($id){
		if(isset($id)){
      $query="select sing.single_id id ,'single' type ".
			",CONCAT('"._SITE."/imgView/',sing.image_id,'/',img.file_type,'/medium') image_url ".
			",CONCAT('"._SITE."/listen/single/',sing.single_id,'/',song.song_id,'/artist/',art.artist_name) location ".
			",art.artist_name artist,song.title ".
			",song.track ".
			", gen.description genre ".
			", pr.description price ".
			" ,time_format(song.length, '%i:%s') length ".
            " FROM ".
            _TBL_ARTIST." art ".
            ", "._TBL_SINGLE." sing ".
            ", "._TBL_GENRE." gen  ".
            ", "._TBL_PRICE." pr ".
            ", "._TBL_SONG." song ".
            ", "._TBL_IMAGE." img ".
            ", "._TBL_ENCODING." rate ".
			", "._TBL_PLAYLIST_ITEM." pli ".
            " where ".
            " pli.item_id = sing.single_id ".
			" AND sing.song_id = song.song_id ".
			" AND sing.image_id = img.image_id ".
            " AND song.genre_id=gen.genre_id  ".
            " and song.rate_id = rate.rate_id ".
            " and pr.price_id = sing.price_id ".
            " and pr.price_id = sing.price_id ".
            " AND art.artist_name = sing.artist_name ".
			" AND pli.item_type= 'single' ".
			" AND pli.playlist_id= '$id' ".
			" AND art.active='Y' AND art.approval_status='A' AND song.active='Y' AND sing.active='Y' AND gen.active ='Y' ";

      return ($this->fetchData($query));
		}
	}

	function getPlayListAlbumItemsForPlayer($id){
		if(isset($id)){
      $query="select
				 distinct alb.album_id id , 'album' type
				,alb.album_name title
				, CONCAT('"._SITE."/shoppingCart/add/album/',alb.album_id,'/PL/Y') visit_url
				,CONCAT('"._SITE."/imgView/id/',img.image_id,'/',img.file_type,'/medium') image_url
				,CONCAT('"._SITE."/pXml/sngList/',alb.album_id) songs
				, alb.album_length length
				,art.artist_name artist
				,CONCAT('"._SITE."/artist/',art.artist_name) artist_url
				, gen.description genre
				, price_description price
             from "._TBL_ALBUM." alb
            , "._TBL_ARTIST." art
			, "._TBL_PLAYLIST_ITEM." pli
			, "._TBL_IMAGE." img
            , "._TBL_GENRE." gen
             WHERE
             pli.item_id = alb.album_id
			AND alb.artist_name = art.artist_name
            AND alb.genre_id = gen.genre_id
            AND alb.image_id = img.image_id
			AND pli.item_type= 'album'
			AND pli.playlist_id= '$id'
			AND art.active='Y' AND art.approval_status='A'  AND gen.active ='Y' AND alb.active ='Y'";

      return ($this->fetchData($query));
		}
	}

	/*  all album playist by pseudo */

	function getPlayListAlbumItemsByPseudoForPlayer($pseudo){
		if(isset($pseudo)){
      $query="select ".
			"	 distinct alb.album_id id , 'album' type ".
			"	,alb.album_name title ".
			", CONCAT('"._SITE."/shoppingCart/add/album/',alb.album_id,'/PL/Y') visit_url ".
			"	,CONCAT('"._SITE."/imgView/',img.image_id,'/',img.file_type,'/medium') image_url ".
			"	,CONCAT('"._SITE."/pXml/sngList/',alb.album_id) songs ".
			"	, alb.album_length length ".
			"	,art.artist_name artist".
			"	,CONCAT('"._SITE."/artist/',art.artist_name) artist_url ".
			"	, gen.description genre ".
			"	, price_description price".
            " from "._TBL_ALBUM." alb ".
            ", "._TBL_ARTIST." art ".
			", "._TBL_PLAYLIST_ITEM." pli ".
			", "._TBL_PLAYLIST." pl ".
			", "._TBL_IMAGE." img ".
            ", "._TBL_GENRE." gen ".
            " WHERE ".
	        " pl.playlist_id = pli.playlist_id ".
            " AND pli.item_id = alb.album_id ".
			" AND alb.artist_name = art.artist_name ".
            " AND alb.genre_id = gen.genre_id".
            " AND alb.image_id = img.image_id".
			" AND pli.item_type= 'album' ".
			" AND pl.pseudo= '$pseudo' ".
			" AND art.active='Y' AND art.approval_status='A'  AND gen.active ='Y' AND alb.active ='Y'";

      return ($this->fetchData($query));
		}
	}

	/*  all playist by pseudo */
	function getPlayListSingleItemsByPseudoForPlayer($pseudo){
		if(isset($pseudo)){
			$query="select sing.single_id id ,'single' type ".
				",CONCAT('"._SITE."/imgView/',sing.image_id,'/',img.file_type,'/medium') image_url ".
				",CONCAT('"._SITE."/listen/single/',sing.single_id,'/',song.song_id,'/artist/',art.artist_name) location ".
				",art.artist_name artist,song.title ".
				",song.track ".
				", gen.description genre ".
				", pr.description price ".
				" ,time_format(song.length, '%i:%s') length ".
	            " FROM ".
	            _TBL_ARTIST." art ".
	            ", "._TBL_SINGLE." sing ".
	            ", "._TBL_GENRE." gen  ".
	            ", "._TBL_PRICE." pr ".
	            ", "._TBL_SONG." song ".
	            ", "._TBL_IMAGE." img ".
	            ", "._TBL_ENCODING." rate ".
				", "._TBL_PLAYLIST_ITEM." pli ".
				", "._TBL_PLAYLIST." pl ".
	            " where ".
	            " pl.playlist_id = pli.playlist_id ".
				" AND pli.item_id = sing.single_id ".
				" AND sing.song_id = song.song_id ".
				" AND sing.image_id = img.image_id ".
	            " AND song.genre_id=gen.genre_id  ".
	            " and song.rate_id = rate.rate_id ".
	            " and pr.price_id = sing.price_id ".
	            " and pr.price_id = sing.price_id ".
	            " AND art.artist_name = sing.artist_name ".
				" AND pli.item_type= 'single' ".
				" AND pl.pseudo= '$pseudo' ".
				" AND art.active='Y' AND art.approval_status='A' AND song.active='Y' AND sing.active='Y' AND gen.active ='Y' ";
			return ($this->fetchData($query));
		}
	}


	/*  all album playist by pseudo */

	function getPlayListAlbumItemsByArtistForPlayer($artist){
		if(trim($artist)){
      $query="select ".
			"	 distinct alb.album_id id , 'album' type ".
			"	,alb.album_name title ".
			", CONCAT('"._SITE."/shoppingCart/add/album/',alb.album_id,'/PL/Y') visit_url ".
			"	,CONCAT('"._SITE."/imgView/id/',img.image_id,'/',alb.img_type,'/medium') image_url ".
			"	,CONCAT('"._SITE."/pXml/sngList/',alb.album_id) songs ".
			"	, alb.album_length length ".
			"	,art.artist_name artist".
			"	,CONCAT('"._SITE."/artist/',art.artist_name) artist_url ".
			"	, gen.description genre ".
			"	, price_description price".
            " from "._TBL_ALBUM." alb ".
            ", "._TBL_ARTIST." art ".
            ", "._TBL_ARTIST." aps ".
			", "._TBL_PLAYLIST_ITEM." pli ".
			", "._TBL_PLAYLIST." pl ".
            ", "._TBL_GENRE." gen ".
            ", "._TBL_IMAGE." img ".
            " WHERE ".
	        " aps.pseudo = pl.pseudo ".
			" AND pl.playlist_id = pli.playlist_id ".
            " AND pli.item_id = alb.album_id ".
			" AND alb.artist_name = art.artist_name ".
            " AND alb.genre_id = gen.genre_id".
            " AND alb.image_id = img.image_id".
			" AND pli.item_type= 'album' ".
			" AND aps.artist_name= '$artist' ".
			" AND art.active='Y' AND art.approval_status='A'  AND gen.active ='Y' AND alb.active ='Y'";

      return ($this->fetchData($query));
		}
	}

	/*  all playist by pseudo */
	function getPlayListSingleItemsByArtistForPlayer($pseudo){
		if(isset($pseudo)){
			$query="select sing.single_id id ,'single' type ".
				",CONCAT('"._SITE."/imgView/',sing.image_id,'/',img.file_type,'/medium') image_url ".
				",CONCAT('"._SITE."/listen/single/',sing.single_id,'/',song.song_id,'/artist/',art.artist_name) location ".
				",art.artist_name artist,song.title ".
				",song.track ".
				", gen.description genre ".
				", pr.description price ".
				" ,time_format(song.length, '%i:%s') length ".
	            " FROM ".
	            _TBL_ARTIST." art ".
	            ", "._TBL_SINGLE." sing ".
	            ", "._TBL_GENRE." gen  ".
	            ", "._TBL_PRICE." pr ".
	            ", "._TBL_SONG." song ".
	            ", "._TBL_IMAGE." img ".
	            ", "._TBL_ENCODING." rate ".
				", "._TBL_PLAYLIST_ITEM." pli ".
				", "._TBL_PLAYLIST." pl ".
	            " where ".
	            " pl.playlist_id = pli.playlist_id ".
				" AND pli.item_id = sing.single_id ".
				" AND sing.song_id = song.song_id ".
				" AND sing.image_id = img.image_id ".
	            " AND song.genre_id=gen.genre_id  ".
	            " and song.rate_id = rate.rate_id ".
	            " and pr.price_id = sing.price_id ".
	            " and pr.price_id = sing.price_id ".
	            " AND art.artist_name = sing.artist_name ".
				" AND pli.item_type= 'single' ".
				" AND pl.pseudo= '$pseudo' ".
				" AND art.active='Y' AND art.approval_status='A' AND sing.active='Y' AND gen.active ='Y' ";
			return ($this->fetchData($query));
		}
	}


	function getAlbumForPlayer($id='',$artistID=''){
    $query= "select album_id id
			,alb.album_name title
			,CONCAT('"._SITE."/shoppingCart/add/album/',album_id,'/PL/Y') visit_url
				,CONCAT('"._SITE."/imgView/',img.image_id,'/',img.file_type,'/medium') image_url
			,CONCAT('"._SITE."/pXml/sngList/',album_id) songs
			,CONCAT('"._SITE."/pXml/sngList/',album_id) singles
            ,IF (TIME_TO_SEC(alb.album_length) >3600,
            time_format(alb.album_length, '%H:%i:%s'),
            time_format(alb.album_length, '%i:%s'))  length

            ,art.artist_name artist
			,CONCAT('"._SITE."/artist/',art.artist_name) artist_url
			, (SELECT count(sta.album_id)  from "._TBL_STAT_ALBUM." sta  where sta.album_id = alb.album_id ) listen
			, gen.description genre
			,CONCAT('"._SITE."/genre/',gen.genre_id) genre_url
			, pr.description price
             from "._TBL_ALBUM." alb , "._TBL_ARTIST." art , "._TBL_GENRE." gen ,"._TBL_IMAGE." img ,"._TBL_PRICE." pr
            WHERE
            alb.artist_name = art.artist_name AND alb.image_id = img.image_id AND alb.genre_id = gen.genre_id and alb.price_id=pr.price_id ";
    if (trim($artistID)) $query.=" AND  alb.artist_name='$artistID' ";
    if (trim($id)) $query.= " AND alb.album_id='$id' ";
    $query.=" AND art.active='Y' AND art.approval_status='A'  AND gen.active ='Y' AND alb.active ='Y'".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  ) ".
              " ORDER by alb.creation_date DESC ";
    return ($this->fetchData($query));
  }
  function getFreeAlbumForPlayer(){
  // echo " inside get album";
    $query= "select album_id id ".
			",alb.album_name title ".
			", CONCAT('"._SITE."/shoppingCart/add/album/',alb.album_id,'/PL/Y') visit_url ".
			"	,CONCAT('"._SITE."/imgView/',alb.image_id,'/',img.file_type,'/medium') image_url ".
			",CONCAT('"._SITE."/pXml/sngList/',album_id) songs ".
			", alb.album_length length ".
			",art.artist_name artist".
			",CONCAT('"._SITE."/artist/',art.artist_name) artist_url ".
            //", alb.nb_songs ".
			", gen.description genre ".
			", (SELECT count(sta.album_id)  from "._TBL_STAT_ALBUM." sta  where sta.album_id = alb.album_id ) listen ".
//			",'10' download ".
			", price_description price".
            " from "._TBL_ALBUM." alb ".
            ", "._TBL_ARTIST." art ".
            ", "._TBL_GENRE." gen ".
            " WHERE ".
            " alb.artist_name = art.artist_name  AND alb.genre_id = gen.genre_id AND alb.image_id=img.image_id  AND alb.price_id ='0.00' ";
    // if (trim($artistID)) $query.=" AND  alb.artist_name='$artistID' ";
    // if (trim($id)) $query.= " AND alb.album_id='$id' ";
    $query.=" AND art.active='Y' AND art.approval_status='A'  AND gen.active ='Y' AND alb.active ='Y'".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  ) ".
              " ORDER by rand() ";
    return ($this->fetchData($query));
  }

    function getSongPerAlbumForPlayer($albumID=''){
        $query=	" select song.song_id id,song.track,time_format(SEC_TO_TIME(TIME_TO_SEC(song.length)), '%i:%s') length,  song.title
                ,CONCAT('"._SITE."/listen/song/',song.song_id) location

            , IF ((select single_id from "._TBL_SINGLE." s where s.song_id=sa.song_id ) IS NULL
                ,CONCAT('"._SITE."/shoppingCart/add/album/',sa.album_id,'/PL/Y')
            	,CONCAT('"._SITE."/shoppingCart/add/single/',(select single_id from "._TBL_SINGLE." s where s.song_id=sa.song_id ),'/PL/Y'))
            visit_url
                ,(
            	CASE alb.preview_length
                	WHEN (alb.preview_length <>999) THEN time_format(SEC_TO_TIME(alb.preview_length), '%i:%s')
                	WHEN 999 THEN time_format(song.length, '%i:%s')
                	ELSE time_format(SEC_TO_TIME(a.preview_length), '%i:%s')
               END
            ) p_length
            , (select price_id from "._TBL_SINGLE." s where s.song_id=sa.song_id ) price
            , (select single_id from "._TBL_SINGLE." s where s.song_id=sa.song_id ) single_id
			FROM "._TBL_ALBUM_SONG." sa,"._TBL_SONG." song , "._TBL_ALBUM." alb, "._TBL_ARTIST." a
            WHERE
            	a.artist_name=alb.artist_name
            	AND alb.album_id=sa.album_id
            	AND sa.album_id ='$albumID'
            	AND sa.song_id=song.song_id
            	AND  song.active='Y'
            	AND (alb.release_date is NULL OR alb.release_date <=now())
                AND sa.album_id ='$albumID'
			 ORDER BY track ASC ";
       // $this->lib->debug($query);
		return ($this->fetchData($query));
  }
    function getSongForPlayer($songID=''){
		$query=	"select ".
			"	song.song_id id ".
			" 	,song.track ".
			" 	,time_format(SEC_TO_TIME(TIME_TO_SEC(song.length)), '%i:%s') length ".
//			"	, sa.album_id ".
			"	, song.title ".
			"	,artist_name artist".
//			"	,  '' price ".
			",CONCAT('"._SITE."/listen/song/',song.song_id) location ".
			" from "._TBL_SONG." song ".
			" where song.song_id='$songID' ";
		return ($this->fetchData($query));
  }

  function getSongPrevForPlayer($songID=''){
		$query=	"select ".
			"	preview_id id ".
			" ,time_format(SEC_TO_TIME(TIME_TO_SEC(length)), '%i:%s') length ".
			"	,title ".
			"	,artist_name artist".
			",CONCAT('"._SITE."/listen/songPrev/',preview_id) location ".
			" from "._TBL_SONG_PREVIEW." ".
			" where preview_id='$songID' ";
		return ($this->fetchData($query));
  }

  function getSingleforPlayer($id='', $artist=''){
		$query= "SELECT sing.single_id id ,art.artist_name artist,song.title ,song.track
                , gen.description genre , pr.description price ,time_format(song.length, '%i:%s') length
			, pr.description price
			, (
                (SELECT count(st.single_id)  from "._TBL_STAT_SINGLE." st  where st.single_id = sing.single_id and sing.song_id=song.song_id)
                + (SELECT count(sta.song_id)  from "._TBL_STAT_SONG." sta  where sta.song_id = song.song_id )
              ) listen
            ,(CASE sing.preview_length
                WHEN (sing.preview_length<>999) THEN time_format(SEC_TO_TIME(sing.preview_length) , '%i:%s')
                WHEN 999 THEN time_format(song.length, '%i:%s')
                ELSE time_format(SEC_TO_TIME(art.preview_length), '%i:%s')
               END
            ) p_length
			,CONCAT('"._SITE."/imgView/',sing.image_id,'/',img.file_type,'/medium') image_url
			,CONCAT('"._SITE."/listen/single/',sing.single_id) location
			,IF(sing.video_id <>'',CONCAT('"._SITE."/view/mov/',sing.video_id),NULL) video
			, CONCAT('"._SITE."/shoppingCart/add/single/',sing.single_id,'/PL/Y') visit_url
            ,CONCAT('"._SITE."/genre/',gen.genre_id) genre_url
			,CONCAT('"._SITE."/artist/',art.artist_name) artist_url
            FROM "._TBL_ARTIST." art , "._TBL_SINGLE." sing , "._TBL_GENRE." gen , "._TBL_PRICE." pr
            , "._TBL_IMAGE." img , "._TBL_SONG." song , "._TBL_ENCODING." rate
             where
            sing.song_id = song.song_id AND sing.image_id=img.image_id  AND song.genre_id=gen.genre_id
            and song.rate_id = rate.rate_id and pr.price_id = sing.price_id  and pr.price_id = sing.price_id
            AND art.artist_name = sing.artist_name ";
			// ,IF(sing.video_id <> NULL,CONCAT('"._SITE."/view/mov/',sing.video_id),NULL) video
    if (trim($artist)) $query.=" AND art.artist_name='$artist' ";
	if (trim($id)) $query.= "and sing.single_id='$id' ";
    $query.=" AND song.active='Y' AND art.active='Y' AND art.approval_status='A' ".
    		" AND sing.active='Y' AND gen.active ='Y' ".
    		" AND (song.release_date <=now() OR song.release_date IS NULL ) ".
    		"ORDER BY sing.creation_date DESC ";
    return ($this->fetchData($query));
  }

  function getVideoforPlayer($id='', $artist=''){
		$query= "SELECT vid.video_id id
				,art.artist_name artist,vid.title
	          ,time_format(vid.length, '%i:%s') length
			, (SELECT count(video_id)  from "._TBL_STAT_VIDEO." st  where st.video_id = vid.video_id ) viewed
			,CONCAT('"._SITE."/view/mov/',vid.video_id) location
			,CONCAT('"._SITE."/video/',vid.video_id) visit_url
			,CONCAT('"._SITE."/imgView/',vid.image_id,'/',img.file_type,'/medium') image_url
			,CONCAT('"._SITE."/artist/',art.artist_name) artist_url
            FROM
            "._TBL_ARTIST." art
            , "._TBL_VIDEO." vid
            , "._TBL_IMAGE." img
             where
             art.artist_name = vid.artist_name and vid.image_id=img.image_id ";
    if (trim($artist)) $query.=" AND art.artist_name='$artist' ";
	if (trim($id)) $query.= "and vid.video_id='$id' ";
    $query.=" AND art.active='Y' AND art.approval_status='A' AND vid.active='Y' ORDER BY vid.creation_date DESC ";
    return ($this->fetchData($query));
  }

  function getVideo($id='', $artist='',$limit){
		$query= "SELECT vid.video_id id,vid.image_id ".
				",art.artist_name,vid.title ".
	         " ,time_format(vid.length, '%i:%s') length ".
			 ", img.file_type img_type ".
//			", ( (SELECT count(st.single_id)  from "._TBL_STAT_SINGLE." st  where st.single_id = sing.single_id and sing.song_id=song.song_id) + (SELECT count(sta.song_id)  from "._TBL_STAT_SONG." sta  where sta.song_id = song.song_id ) ) listen ".
            "FROM ".
            _TBL_ARTIST." art ".
            ", "._TBL_VIDEO." vid ".
			", "._TBL_IMAGE." img ".
            " where ".
            " art.artist_name = vid.artist_name ".
			" AND vid.image_id=img.image_id  ";
    if (trim($artist)) $query.=" AND art.artist_name='$artist' ";
	if (trim($id)) $query.= "and vid.video_id='$id' ";
    $query.=" AND art.active='Y' AND art.approval_status='A' AND vid.active='Y' ORDER BY vid.creation_date DESC ";
	if($limit>0) $query.=" LIMIT 0,$limit ";
    return ($this->fetchData($query));
  }


  function getSingleSelectionforPlayer($type=''){
		$query= "SELECT sing.single_id id ".
				",art.artist_name artist,song.title ".
				",song.track ".
				", gen.description genre ".
				", pr.description price ".
	         " ,time_format(song.length, '%i:%s') length ".
			", pr.description price ".
			",( (SELECT count(sta.song_id) from "._TBL_STAT_SONG." sta where sta.song_id = song.song_id ) ".
			"	+ (SELECT count(sta1.single_id) from "._TBL_STAT_SINGLE." sta1 where sta1.single_id = sing.single_id ) ".
			" ) listen ".
			",CONCAT('"._SITE."/imgView/',sing.image_id,'/',img.file_type,'/medium') image_url ".
			",CONCAT('"._SITE."/listen/single/',sing.single_id) location ".
			", CONCAT('"._SITE."/shoppingCart/add/single/',sing.single_id,'/PL/Y') visit_url ".
			",CONCAT('"._SITE."/artist/',art.artist_name) artist_url ".
            "FROM ".
            _TBL_ARTIST." art ".
            ", "._TBL_SINGLE." sing ".
            ", "._TBL_SELECTION." sel ".
            ", "._TBL_GENRE." gen  ".
            ", "._TBL_PRICE." pr ".
            ", "._TBL_IMAGE." img ".
            ", "._TBL_SONG." song ".
            ", "._TBL_ENCODING." rate ".
            " where ".
            " sel.id=sing.single_id ".
			" AND sing.song_id = song.song_id ".
            " AND sing.image_id=img.image_id  ".
            " AND song.genre_id=gen.genre_id  ".
            " and song.rate_id = rate.rate_id ".
            " and pr.price_id = sing.price_id ".
            " and pr.price_id = sing.price_id ".
            " AND art.artist_name = sing.artist_name ".
			" AND sel.id_type='single'";
		if (trim($type)) $query.= "and sel.sel_type='$type' ";
		$query.="  AND song.active='Y' AND art.active='Y' AND art.approval_status='A' ".
				" AND sing.active='Y' AND gen.active ='Y' ".
				" AND (song.release_date <=now() OR song.release_date IS NULL ) ".
				" ORDER BY rand() ";
    return ($this->fetchData($query));
  }
    function getFreeSingleforPlayer(){
		$query= "SELECT sing.single_id id ".
				",art.artist_name artist,song.title ".
				",song.track ".
				", gen.description genre ".
				", pr.description price ".
	         " ,time_format(song.length, '%i:%s') length ".
			", pr.description price ".
			", (SELECT count(sta.song_id)  from "._TBL_STAT_SONG." sta  where sta.song_id = song.song_id ) listen ".
			",CONCAT('"._SITE."/imgView/',sing.image_id,'/',img.file_type,'/medium') image_url ".
			",CONCAT('"._SITE."/listen/single/',sing.single_id) location ".
			", CONCAT('"._SITE."/shoppingCart/add/single/',sing.single_id,'/PL/Y') visit_url ".
			",CONCAT('"._SITE."/artist/',art.artist_name) artist_url ".
            "FROM ".
            _TBL_ARTIST." art ".
            ", "._TBL_SINGLE." sing ".
            ", "._TBL_GENRE." gen  ".
            ", "._TBL_PRICE." pr ".
            ", "._TBL_IMAGE." img ".
            ", "._TBL_SONG." song ".
            ", "._TBL_ENCODING." rate ".
            " where ".
            " sing.song_id = song.song_id ".
            " AND sing.image_id=img.image_id  ".
            " AND song.genre_id=gen.genre_id  ".
            " and song.rate_id = rate.rate_id ".
            " and pr.price_id = sing.price_id ".
            " and pr.price_id = sing.price_id ".
            " AND art.artist_name = sing.artist_name ".
			" AND sing.price_id = '0.00'";
    $query.="  AND song.active='Y' AND art.active='Y' AND art.approval_status='A' AND sing.active='Y' AND gen.active ='Y' ORDER BY rand() ";
    return ($this->fetchData($query));
  }

    function getRecentAlbumForPlayer(){
  // echo " inside get album";
    $query= "select alb.album_id id ".
			",alb.album_name title ".
    		", CONCAT('"._SITE."/shoppingCart/add/album/',alb.album_id,'/PL/Y') visit_url ".
			",CONCAT('"._SITE."/imgView/',alb.image_id,'/',img.file_type,'/medium') image_url ".
			",CONCAT('"._SITE."/pXml/sngList/',alb.album_id) songs ".
			", alb.album_length length ".
			",art.artist_name artist".
			",CONCAT('"._SITE."/artist/',art.artist_name) artist_url ".
            //", alb.nb_songs ".
			", gen.description genre ".
			//",alb.rate_description ".
			", price_description price".
            " from "._TBL_ALBUM." alb ".
            ", "._TBL_STAT_ALBUM." sa ".
            ", "._TBL_ARTIST." art ".
            ", "._TBL_GENRE." gen ".
            ", "._TBL_IMAGE." img ".
            " WHERE ".
            " alb.artist_name = art.artist_name ".
			" and alb.album_id = sa.album_id ".
            " AND alb.genre_id = gen.genre_id".
            " AND alb.image_id = gen.image_id".
			" AND sa.date=CURDATE() ".
			" AND art.active='Y' AND art.approval_status='A'  AND gen.active ='Y' AND alb.active ='Y'".
    		" AND (alb.release_date <= NOW() OR alb.release_date IS NULL  ) ".
              " ORDER by sa.time DESC LIMIT 0,1";
    return ($this->fetchData($query));
  }

  function getRecentSingleForPlayer(){
		$query= "SELECT sing.single_id id ".
			",CONCAT('"._SITE."/imgView/',sing.image_id,'/',img.file_type,'/medium') image_url ".
			",CONCAT('"._SITE."/listen/single/',sing.single_id,'/',song.song_id,'/artist/',art.artist_name) location ".
			",CONCAT('"._SITE."/view/mov/',sing.video_id) video ".
		",art.artist_name artist,song.title ".
		",song.track ".
         ", gen.description genre ".
         ", pr.description price ".
		 //", rate.description rate_description ".
         " ,time_format(song.length, '%i:%s') length ".
            "FROM ".
            _TBL_ARTIST." art ".
            ", "._TBL_SINGLE." sing ".
            ", "._TBL_GENRE." gen  ".
            ", "._TBL_PRICE." pr ".
			", "._TBL_STAT_SONG." ss ".
            ", "._TBL_SONG." song ".
            ", "._TBL_IMAGE." img ".
            ", "._TBL_ENCODING." rate ".
            " where ".
            " sing.song_id = song.song_id ".
			" AND sing.image_id = img.image_id ".
            " AND song.genre_id=gen.genre_id  ".
            " and song.rate_id = rate.rate_id ".
			" AND song.song_id = ss.song_id ".
            " and pr.price_id = sing.price_id ".
            " and pr.price_id = sing.price_id ".
            " AND art.artist_name = sing.artist_name ".
			" AND ss.date=CURDATE() ".
			"  AND song.active='Y' AND art.active='Y' AND art.approval_status='A' ".
            " AND sing.active='Y' AND gen.active ='Y' ".
            " AND (song.release_date <=now() OR song.release_date IS NULL ) ".
            " ORDER BY sing.creation_date DESC ";
    return ($this->fetchData($query));
  }
  /* ads for player*/
  function getAdsForPlayer($lang=''){
	if(trim($lang)){
		$query= "SELECT line1 ,line2 ,line3,url ,timer timeout ,image_url image
				,CONCAT('"._SITE."/aT/',ads_id) track
	             FROM ".
	            _TBL_PLAYER_ADS." ad
	             where
	             lang in ('$lang','ALL')
	             AND active='Y'
	             order by rand()  ";
	    return ($this->fetchData($query));
	}
  }

  /*
   * List all single for a selected genre
   */
  function getSinglefilename($varID=''){
    $query= "SELECT distinct sing.single_id, song.pseudo,song.file_name, song.preview_id, (select file_name from "._TBL_SONG_PREVIEW." p where p.preview_id=song.preview_id) preview_file_name ".
    ", sing.price_id, sing.preview_length song_length, art.preview_length artist_length,art.preview_quality artist_quality ".
            "FROM ".
            _TBL_ARTIST." art ".
            ","._TBL_SINGLE." sing ".
            ", "._TBL_SONG." song ".
            " where ".
            " sing.artist_name= art.artist_name ".
            " AND sing.song_id = song.song_id ".
            " AND sing.single_id='$varID'  ".
            "  AND song.active='Y' AND art.active='Y' AND art.approval_status='A'  AND sing.active='Y' ";
    return ($this->fetchData($query));
  }

  function getSongfilename($varID=''){
    $query= "SELECT distinct song.pseudo,song.file_name,song.song_id, song.preview_id, (select file_name from "._TBL_SONG_PREVIEW." p where p.preview_id=song.preview_id) preview_file_name ".
/*		" (case sub.user_type
			when 'artist' then (SELECT preview_length  from artists A where A.pseudo = sub.pseudo )
			when 'manager' then (SELECT preview_length from artist_managers p1 where p1.pseudo = sub.pseudo )
			else NULL
		END
		) artist_length ".
		*/
		", art.preview_length  artist_length ".
		", art.preview_quality artist_quality ".
		", (SELECT preview_length  from "._TBL_ALBUM." alb, "._TBL_ALBUM_SONG." albs where song.song_id = albs.song_id AND albs.album_id=alb.album_id group by song.song_id) length ".
		", (SELECT price_id  from "._TBL_ALBUM." alb, "._TBL_ALBUM_SONG." albs where song.song_id = albs.song_id AND albs.album_id=alb.album_id group by song.song_id) price_id ".
//		", (SELECT alb.artist_name  from "._TBL_ALBUM." alb, "._TBL_ALBUM_SONG." albs where song.song_id = albs.song_id AND albs.album_id=alb.album_id ) artist ".
        "FROM ".
            _TBL_SUBSCRIBER." sub , "._TBL_SONG." song , "._TBL_ARTIST." art ".
            " where ".
            " art.pseudo=sub.pseudo ".
            " AND sub.pseudo = song.pseudo ".
            " AND song.song_id='$varID'  ".
            "  AND song.active='Y' AND sub.active='Y' ";
			//"AND song.active='Y' ";
			// echo "query=> $query";
    return ($this->fetchData($query));
  }

  function getSongPreviewfilename($varID=''){
    $query= "SELECT pseudo,file_name,preview_id id, rate_id FROM "._TBL_SONG_PREVIEW." where preview_id='$varID'  ";
    return ($this->fetchData($query));
  }

  function getVideofilename($varID=''){
    $query= "SELECT distinct vid.pseudo,vid.flv_file_name file_name,vid.video_id ".
        "FROM ".
            _TBL_SUBSCRIBER." sub , "._TBL_VIDEO." vid , "._TBL_ARTIST." art ".
            " where ".
            " sub.pseudo = vid.pseudo ".
            " AND vid.video_id='$varID'  ".
            " AND sub.active='Y' ";
			//"AND song.active='Y' ";
			// echo "query=> $query";
    return ($this->fetchData($query));
  }

 /*
   * Retrieve  artist detail
   */
  function retrieveArtistFullDetail($pseudo){
    $query= "select A.*, S.*  ".
            " from "._TBL_ARTIST." A, "._TBL_SUBSCRIBER." S ".
            " where ".
            " S.pseudo = A.pseudo ".
            " AND S.pseudo = '$pseudo'";
    return ($this->fetchData($query));
  }

  function retrieveArtistPaymentinfo($pseudo){
    $query= "select P.*  from "._TBL_PAYMENT_ARTIST_CHOICE." P, "._TBL_SUBSCRIBER." S ".
            " where S.pseudo = P.pseudo AND S.pseudo = '$pseudo' AND is_active='Y'";
    return ($this->fetchData($query));
  }

  function retrievePaymentArtistList(){
    $query= "select payment_choice_id, description, fees from "._TBL_PAYMENT_CHOICES." where 1 AND is_active='Y'";
    return ($this->fetchData($query));
  }

  function createPaymentChoice ($pseudo,$post){
    $errorFlag=false;
    $result=$this->db->query("update "._TBL_PAYMENT_ARTIST_CHOICE." set is_active='N' where pseudo='".$pseudo."'");
    $query="insert "._TBL_PAYMENT_ARTIST_CHOICE.
          " (pseudo, first_name, last_name, manager_name, account_reference,payment_choice_id, creation_date, is_active) ".
          " values ('".$pseudo."','".addslashes($post['first_name'])."','".addslashes($post['last_name'])."' ".
          ",'".addslashes($post['manager_name'])."' ,'".addslashes($post['account_reference'])."' ,'".addslashes($post['payment_choice_id'])."' ,now(), 'Y')";
    $result=$this->db->query($query);
    if(!$result || $this->db->error()) $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }

  function retrieveManagerArtistFullDetail($pseudo,$artist=''){
    $query= "select A.* ".
            " from "._TBL_ARTIST." A, "._TBL_SUBSCRIBER." S ".
            " where ".
            " S.pseudo = A.pseudo ".
            " AND S.pseudo = '$pseudo'";
	if(trim($artist) && isset($artist)) $query.=" and A.artist_name='$artist'";
    return ($this->fetchData($query));
  }
  function retrieveManagerFullDetail($pseudo){
    $query= "select P.*, S.*  ".
            " from "._TBL_MANAGER." P, "._TBL_SUBSCRIBER." S ".
            " where ".
            " S.pseudo = P.pseudo ".
            " AND S.pseudo = '$pseudo'";
    return ($this->fetchData($query));
  }

  function getArtistDetail($artistID){
	$query= "select A.artist_name,
			C.description country  from "._TBL_ARTIST." A,"._TBL_COUNTRY." C, "._TBL_MANAGER." P
			where P.pseudo = A.pseudo AND A.country_id = C.country_id AND p.manager_name = '$artistID' AND A.active='Y' AND P.active='Y'";

	// echo "<br>query =>".$query;
			return ($this->fetchData($query));}

	function getArtistInfo($artistID){
		$query= "select A.artist_name, A.website, A.profile_views, A.personal_detail
			,C.description country  from "._TBL_ARTIST." A,"._TBL_COUNTRY." C
			where A.country_id = C.country_id AND A.artist_name = '$artistID' AND A.active='Y'";

	// echo "<br>query =>".$query;
			return ($this->fetchData($query));
	}

  function getLabelDetail($ID){$query= "select A.manager_name, A.website, ".
	"C.description country  from "._TBL_ARTIST." A,"._TBL_COUNTRY." C  where A.country_id = C.country_id AND A.manager_name = '$artistID' AND A.active='Y'";return ($this->fetchData($query));}

  function retrieveSubscriberDetail($varID){$query= "select * from "._TBL_SUBSCRIBER." where pseudo = '$varID'";return ($this->fetchData($query));}
  function retrieveArtistInfo($artistID){$query= "select image_id, personal_detail from "._TBL_ARTIST." where pseudo = '$artistID'"; return ($this->fetchData($query)); }
  function retrieveArtistPeudo($artistID){$query= "select pseudo from "._TBL_ARTIST." where artist_name = '$artistID'"; return ($this->fetchData($query)); }

  /*
   * List all single for a selected album
   */
  function getSinglePerAlbum($albumID='',$artistID=''){
  $query=	" select song.song_id,song.track,time_format(SEC_TO_TIME(TIME_TO_SEC(song.length)), '%i:%s') length, sa.album_id, song.title
            , (select single_id from "._TBL_SINGLE." s where s.song_id=sa.song_id ) single_id
            , (select price_id from "._TBL_SINGLE." s where s.song_id=sa.song_id ) price_id
			from "._TBL_ALBUM_SONG." sa,"._TBL_SONG." song
			where sa.album_id ='$albumID' and sa.song_id=song.song_id and  song.active='Y'
            AND sa.album_id ='$albumID'
			 ORDER BY track ASC ";
 // echo "<br>query ->".$query;
    return ($this->fetchData($query));
  }

  function getSinglePerAlbum_old($albumID='',$artistID=''){
  $query=	" select * from ".
			" (select song.song_id,song.track,time_format(SEC_TO_TIME(TIME_TO_SEC(song.length)), '%i:%s') length, sa.album_id, song.title, '' single_id, '' price_id ".
			" from "._TBL_ALBUM_SONG." sa,"._TBL_SONG." song ".
			" where sa.album_id ='$albumID' and sa.song_id=song.song_id and  song.active='Y' AND sa.song_id not in ( ".
			" 	select s1.song_id from "._TBL_SINGLE." s1, "._TBL_ALBUM_SONG." sa1 where ".
            " s1.song_id=sa1.song_id and sa1.album_id ='$albumID' ".
			" ) ".
			" UNION ".
			" select song.song_id,song.track,time_format(SEC_TO_TIME(TIME_TO_SEC(song.length)), '%i:%s') length,sa.album_id ".
            ", song.title ,sing.single_id,sing.price_id ".
			" from "._TBL_ALBUM_SONG." sa,"._TBL_SONG." song , "._TBL_SINGLE." sing ".
			" where sa.album_id ='$albumID' and sa.song_id=song.song_id and sa.song_id=sing.song_id AND song.active='Y'".
			"  ) as album ORDER BY track ASC ";
    return ($this->fetchData($query));
  }


/*
   * Retrieve the list of countrie from DB
   */
  function getCountryList(){
    $query= "select country_id id, description from "._TBL_COUNTRY." where active='Y' ORDER by description"; return ($this->fetchData($query));
  }

/*
   * Retrieve the list of countrie from DB to use in Forms
   */
  function getCountryFormList(){
    $query= "select country_id, description from "._TBL_COUNTRY." where active='Y' ORDER by description";
    $result = $this->db->query($query);
     if($this->db->nbrecord()>0){
         while ($r= mysql_fetch_object($result)){
             $myArray['id'][]=$r->country_id;
             $myArray['description'][]=stripslashes($r->description);
         }
     }
     return($myArray);
  }

  function getPeriodFormList(){
    $query= "select period_id, period_name_fr from "._TBL_PERIOD." where 1 ORDER by period_name_fr";
    $result = $this->db->query($query);
     if($this->db->nbrecord()>0){
         while ($r= mysql_fetch_object($result)){
             $myArray['id'][]=$r->period_id;
             $myArray['description'][]=stripslashes($r->period_name_fr);
         }
     }
     return($myArray);
  }


	/*
	 * Record visitor payments
	 */
	function recordVisitorPayment($paymentCode,$partnerNum){
		$query=" insert "._TBL_PAYMENT_AUDIT."( payment_date, partner_id, payment_code) VALUES (now(),'$partnerNum','$paymentCode')";
		$result=$this->db->query($query); if ($result) return(true); else return(false);
	}

    /*
    * Retrieve a prepayment for the selected partner
    *
		*/

    function getPartnerPrepayment($code) {
      $query= " select * ".
              " from "._TBL_PREPAYMENT." p ".
              "where ".
              "	p.active='Y'  ".
              "	AND p.credit_remaining >0  ".
              " AND p.partner_id = '$code' ".
			  " LIMIT 0,1 ";
      return ($this->fetchData($query));
	}


	/*
	* Check if a pseudo already exists for an artist
	*/
	function pseudoExist($var){
	  $result = $this->db->query("SELECT count(pseudo) FROM "._TBL_SUBSCRIBER." where pseudo='$var'");
    if ($this->db->error()) return(false);
    else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true); else return (false);}
	}
	function artistNameExist($var){
	  $result = $this->db->query("SELECT count(artist_name) FROM "._TBL_ARTIST." where artist_name='".addslashes($var)."'");
    if ($this->db->error()) return(false);
    else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true); else return (false);}
	}
	function managerNameExist($var){
	  $result = $this->db->query("SELECT count(manager_name) FROM "._TBL_MANAGER." where manager_name='".addslashes($var)."'");
    if ($this->db->error()) return(false);
    else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true); else return (false);}
	}
	/*
	* Check if an email already exists
	*/
	function emailExist($data){
	  $result = $this->db->query("SELECT count(email) FROM "._TBL_SUBSCRIBER." where email='$data'");
    if ($this->db->error()) return(false);
    else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true); else return (false);}
	}

	/*
	* Check if a single already exists for the given Pseudo
	*/
	function singleExist($song_id){
	  $result = $this->db->query("SELECT count(song_id) FROM "._TBL_SINGLE." where song_id='$song_id'");
    if (!$result || $this->db->error()) return(false); else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true); else return (false);}
	}

	/*
	* Check if a rate is valid
	*/
	function rateExist($rate_id){
	  $result = $this->db->query("SELECT count(rate_id) FROM "._TBL_ENCODING." where rate_id='$rate_id'");
    if (!$result || $this->db->error()) return(false); else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true); else return (false);}
	}

	/*
	* Check if a single already exists for the given Pseudo
	*/
	function fileExist($fileName,$pseudo,$doc){
  	if($doc=='img') $query = "SELECT count(image_id) FROM "._TBL_IMAGE." where file_name='$fileName' AND pseudo='$pseudo'";
		elseif($doc=='song') $query = "SELECT count(song_id) FROM "._TBL_SONG." where file_name='$fileName' AND pseudo='$pseudo'";
		elseif($doc=='mov') $query = "SELECT count(video_id) FROM "._TBL_VIDEO." where file_name='$fileName' AND pseudo='$pseudo'";
		elseif($doc=='prevSong') $query = "SELECT count(preview_id) FROM "._TBL_SONG_PREVIEW." where file_name='$fileName' AND pseudo='$pseudo'";
		$result = $this->db->query($query); if (!$result || $this->db->error()) return(false);
		else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true); else return (false);}
	}

	/*
	* Check if an album already exists for the given Pseudo
	*/
	function albumExist($title,$artist){
	  $result = $this->db->query("SELECT count(album_name) FROM "._TBL_ALBUM." where album_name='$title' AND artist_name='$artist'");
    if (!$result || $this->db->error()) return(false);
    else{list($item) = mysql_fetch_row($result);if(isset($item) && $item >0) return (true);else return (false);}
	}

	function eventExist($startDate,$endDate,$artist){
	  $result = $this->db->query("SELECT count(title) FROM "._TBL_ART_EVENT." where from_date='".$this->lib->dateFr2Sql($startDate,'/')."' AND to_date='".$this->lib->dateFr2Sql($endDate,'/')."' and artist_name='$artist'");
    if (!$result || $this->db->error()) return(false);
    else{list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true);else return (false);}
	}

    function isSongRateHigh($songID,$pseudo){
        unset($var);
        $var=array();
	  $result = $this->db->query("SELECT rate_id,title FROM "._TBL_SONG." where song_id='".$songID."' and pseudo='$pseudo'");
      if (!$result || $this->db->error()) $var['resp']=false;
        else{
        list($var['rate'],$var['title']) = mysql_fetch_row($result);
         if(isset($var['rate']) && (int) $var['rate'] >=192000)   $var['resp']=1;
         else $var['resp']=0;
         }
    return($var)     ;
    }


	function addSongAlbum($albumID, $arrayVar){
        /* loop to add all songs for an album */
        $this->db->query("delete FROM "._TBL_ALBUM_SONG." WHERE album_id='$albumID'");
        $query="INSERT "._TBL_ALBUM_SONG." (album_id, song_id) VALUES ";
        unset($values);
        foreach($arrayVar as $key=>$data){
          if(isset($values)) $values.=", "; else $values=" ";
          $values .= " ( '".$albumID."' ,'".$data."')";
        }
        $query.= $values; $result=$this->db->query($query);
        if(!$result || $this->db->error()) return(false);
        else{
            if ($this->updateAlbumSongInfo($albumID)) return(true);
            else return(false);
        }
	}

    function updateAlbumSongInfo($id){
        $query = "UPDATE "._TBL_ALBUM." alb SET
                nb_songs = (
                    SELECT COUNT(sa.song_id)
                    FROM "._TBL_ALBUM_SONG." sa, "._TBL_SONG." f
                    WHERE sa.album_id = alb.album_id AND sa.song_id = f.song_id)
                , album_length= (
                    SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(f.length)))
                    FROM album_songs sa, "._TBL_SONG." f
                    WHERE f.song_id = sa.song_id AND sa.album_id =alb.album_id )
                ,album_size= (
                    SELECT SUM(f.filesize)
                    FROM "._TBL_ALBUM_SONG." sa, "._TBL_SONG." f
                    WHERE sa.song_id = f.song_id AND sa.album_id = alb.album_id)
                WHERE album_id='$id'";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) return(false); else return(true);

    }


	function addNewSingleSelection($arrayVar){
	    /* Boucle pour ajouter les participant du voyage*/
	    $this->db->query("delete FROM "._TBL_SELECTION." WHERE sel_type='myspace'");
	    $query="INSERT "._TBL_SELECTION." (sel_type,id_type, id) VALUES ";
	    unset($values);
	    foreach($arrayVar as $key=>$data){
	      if(isset($values)) $values.=", "; else $values=" ";
	      $values .= " ( 'myspace' ,'single' ,'".$data."')";
	    }
	    $query.= $values; $result=$this->db->query($query); if(!$result || $this->db->error()) return(false); else return(true);
	}

	function addNewMusicPref($arrayVar,$pseudo){
	    /* Boucle pour ajouter les participant du voyage*/
	    $this->db->query("delete FROM "._TBL_MUSIC_PREF." WHERE pseudo='$pseudo'");
	    if(is_array($arrayVar)){
	    	$query="INSERT "._TBL_MUSIC_PREF." (genre_id,pseudo) VALUES ";
	    	unset($values);
		    foreach($arrayVar as $key=>$data){
		      if(isset($values)) $values.=", "; else $values=" ";
		      $values .= " ( '".$data."','$pseudo')";
		    }
		    $query.= $values; $result=$this->db->query($query); if(!$result || $this->db->error()) return(false); else return(true);
	    }
	    else return(true);
	}


  function getSingleSelection($genreID){
    if(trim($genreID)){
      $query= "SELECT sing.single_id id , CONCAT(art.artist_name,' - ',song.title, ' ( ',time_format(song.length, '%i:%s'),' mins )') description ".
              "FROM "._TBL_SINGLE." sing , "._TBL_SONG." song, "._TBL_ARTIST." art ".
			  "WHERE art.artist_name=sing.artist_name AND sing.song_id=song.song_id  AND song.active='Y'  and song.genre_id='$genreID' ".
			  "AND sing.active='Y' AND art.active='Y' ORDER by art.artist_name ASC";
      return ($this->fetchData($query));
    }
  }

	function getSingleSelectionFormList($type='myspace'){
		$query= "SELECT sing.single_id id , CONCAT(art.artist_name,' - ',song.title, ' ( ',time_format(song.length, '%i:%s'),' mins )') description ".
	            "FROM "._TBL_SINGLE." sing , "._TBL_SONG." song, "._TBL_ARTIST." art, "._TBL_SELECTION." sel ".
				"WHERE sel.id = sing.single_id AND sing.song_id = song.song_id AND sing.artist_name=art.artist_name AND sel.id_type='single' AND sel.sel_type='$type'".
				"AND song.active='Y' AND art.active='Y' ORDER by art.artist_name ASC";
		return ($this->fetchData($query));
	}

	function getGenrePrefFormList($pseudo){
		$query= "SELECT g.genre_id id, g.description description ".
	            "FROM "._TBL_MUSIC_PREF." mp , "._TBL_GENRE." g ".
				"WHERE mp.genre_id = g.genre_id and mp.pseudo='$pseudo'".
				" AND g.active='Y' ORDER by g.description ASC";
		return ($this->fetchData($query));
	}


	  /*
	   * Register POST information for a new subscriber
	   */
  function registerNewBuyer ($post,$type='buyer'){
    $errorFlag=false;
    $query="insert "._TBL_SUBSCRIBER.
          " (first_name, last_name, pseudo, title, country_id,".
          " home_phone,cell_phone,work_phone, email, active,email_confirmed,newsletter,user_type,creation_date) ".
          " values ( ".
          "	'".addslashes($post['first_name'])."','".addslashes($post['last_name'])."','".addslashes($post['pseudo'])."' ".
          ",'".addslashes($post['title'])."','".$post['country']."','".addslashes($post['home_phone'])."','".addslashes($post['cell_phone'])."' ".
          ",'".addslashes($post['work_phone'])."','".addslashes($post['email'])."','Y','N','".$post['newsletter']."','".$type."', now()".
          " )";
    $result=$this->db->query($query);
    if(!$result || $this->db->error()) $errorFlag=true;
    else{
      if(!$this->createBuyerPref($post['pseudo'],trim($post['password']))) $errorFlag=true;
    }
    if ($errorFlag) return(false) ; else return(true);
  }

  function createBuyerPref($pseudo,$password){
    $errorFlag=false;
    $query="insert "._TBL_SUBSCRIBER_PREF.
        " (password, nb_title , nb_album, language_pref,pseudo, page_name,blog_active,space_active,music_pref_active,creation_date) ".
        " values ".
        " ( ".
        "	'".addslashes($password)."' ".
        ",'"._NB_TITLE_PER_PAGE."' ".
        ",'"._NB_ALBUM_PER_PAGE."' ".
        ",'".$_SESSION['LANG']."' ".
        ",'".$pseudo."' ".
        ",'".$pseudo."' ".
  			", 'Y'".
  			", 'Y'".
  			", 'Y'".
  			", now()".
  			" )";
    $result=$this->db->query($query);
    if(!$result || $this->db->error()) $errorFlag=true; if ($errorFlag) return(false) ; else return(true);
  }

	  /*
	   * Register POST information for a new artist
	   */
	  function registerNewArtist ($post){
	    $errorFlag=false;
	    if($this->registerNewBuyer($post,'artist')){
				if(!$this->createArtistInfo($post)) $errorFlag=true;
			}
			else $errorFlag=true;
	    if ($errorFlag) return(false) ; else return(true);
    }

	function registerNewManager ($post){
	    $errorFlag=false;
	    if($this->registerNewBuyer($post,'manager')){
				if(!$this->createManagerInfo($post)) $errorFlag=true;
			}
			else $errorFlag=true;
	    if ($errorFlag) return(false) ; else return(true);
    }

  function createManagerInfo($post){
    $errorFlag=false;
    $query="insert "._TBL_MANAGER.
		            " (first_name, last_name, manager_name,company_reg_num,vat_id, pseudo, title,address_line1, address_line2, city, zip_code, country_id,".
		            " website,cell_phone, work_phone,fax,preview_length,approval_status,active,creation_date) ".
		            " values ".
		            " ( ".
		            "	'".addslashes($post['first_name'])."' ".
		            ",'".addslashes($post['last_name'])."' ".
		            ",'".addslashes($post['manager_name'])."' ".
		            ",'".addslashes($post['company_reg_num'])."' ".
		            ",'".addslashes($post['vat_id'])."' ".
		            ",'".addslashes($post['pseudo'])."' ".
		            ",'".addslashes($post['title'])."' ".
		            ",'".addslashes($post['address_line1'])."' ".
		            ",'".addslashes($post['address_line2'])."' ".
		            ",'".addslashes($post['city'])."' ".
		            ",'".addslashes($post['zip_code'])."' ".
		            ",'".$post['country']."' ".
		            ",'".addslashes($post['web_site'])."' ".
		            ",'".addslashes($post['cell_phone'])."' ".
		            ",'".addslashes($post['work_phone'])."' ".
		            ",'".addslashes($post['fax'])."' ".
		            ",'".$post['preview_length']."' ".
		            ",'A' ".
					",'Y' ".
					", now()".
					" )";
					$result=$this->db->query($query);
					if(!$result || $this->db->error()) $errorFlag=true;
			 if ($errorFlag) return(false) ; else return(true);
	  }
  function createArtistInfo($post,$pseudo=''){
	if(!trim($post['pseudo'])) $_pseudo=$pseudo; else $_pseudo=$post['pseudo'];
	//if($post['exclusivity_flag']=='Y') $rate='75'; else
    $rate='75';
    $errorFlag=false;
		if(trim($_pseudo)){
			$query="insert "._TBL_ARTIST.
		            " (first_name, last_name,artist_name, company_name,vat_id, pseudo, title,address_line1, address_line2, city, zip_code, country_id,".
		            " website, personal_detail, fax,cell_phone,work_phone,company_id ,copyright_subscriber_num, preview_length,preview_quality,approval_status,active,creation_date) ".
		            " values ".
		            " ( ".
		            "	'".addslashes($post['first_name'])."' ".
		            ",'".addslashes($post['last_name'])."' ".
		            ",'".addslashes($post['artist_name'])."' ".
		            ",'".addslashes($post['company_name'])."' ".
		            ",'".addslashes($post['vat_id'])."' ".
		            ",'".addslashes($_pseudo)."' ".
		            ",'".addslashes($post['title'])."' ".
		            ",'".addslashes($post['address_line1'])."' ".
		            ",'".addslashes($post['address_line2'])."' ".
		            ",'".addslashes($post['city'])."' ".
		            ",'".addslashes($post['zip_code'])."' ".
		            ",'".$post['country']."' ".
		            ",'".addslashes($post['website'])."' ".
					",'".addslashes($post['personal_detail'])."' ".
		            ",'".addslashes($post['fax'])."' ".
		            ",'".addslashes($post['cell_phone'])."' ".
		            ",'".addslashes($post['work_phone'])."' ".
		            ", '".$post['company_id']."' ".
		            ", '".$post['copyright_subscriber_num']."' ".
		            ", '".$post['preview_length']."' ".
		            ", '".$post['preview_quality']."' ".
		            ",'A' ".
					",'Y' ".
					", now()".
					" )";
			$result=$this->db->query($query);
			if(!$result || $this->db->error()) $errorFlag=true;
			else{
                $q_rate="insert into "._TBL_ARTIST_RATES." (pseudo, artist_name,revenue_rate,date_active_from,date_active_to,creation_date) values".
                    "('".addslashes($_pseudo)."' ".
                    ",'".addslashes($post['artist_name'])."' ".
                    ",'".$rate."' ".
                    ",now() ".
//                    ",DATE_ADD(CURDATE(), INTERVAL 18 MONTH) ".
                    ",NULL ".
                    ",now() )";
				$resultR=$this->db->query($q_rate);
			}
		}
	if ($errorFlag) return(false) ; else return(true);
	  }

	  /*
	   * Register POST album information
	   */
  function updateArtistProfile ($pseudo,$post){
    $errorFlag=false;
    if(!$this->updateSubscriberProfile($pseudo,$post)) $errorFlag=true;
    else{
      $query="UPDATE "._TBL_ARTIST.
        " SET ".
        " last_name='".addslashes($post['last_name'])."' ".
        ",first_name='".addslashes($post['first_name'])."' ".
        ",company_name='".addslashes($post['company_name'])."' ".
        ",company_id='".addslashes($post['company_id'])."' ".
        ",copyright_subscriber_num='".addslashes($post['copyright_subscriber_num'])."' ".
        ",vat_id='".addslashes($post['vat_id'])."' ".
        ",address_line1='".addslashes($post['address_line1'])."' ".
        ",address_line2='".addslashes($post['address_line2'])."' ".
        ",city='".addslashes($post['city'])."' ".
        ",title='".addslashes($post['title'])."' ".
        ",website='".addslashes($post['website'])."' ".
		",preview_length='".addslashes($post['preview_length'])."' ".
		",preview_quality='".addslashes($post['preview_quality'])."' ".
		",personal_detail='".addslashes($post['personal_detail'])."' ".
        ",fax='".addslashes($post['fax'])."' ".
        ",zip_code='".addslashes($post['zip_code'])."' ".
        ",country_id = '".addslashes($post['country_id'])."'".
        ",update_date=now() ".
        " WHERE pseudo='".$pseudo."'";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    }
    if ($errorFlag) return(false) ; else return(true);
  }
  function updateManagerArtist($pseudo,$post){
    $errorFlag=false;
    $query="UPDATE "._TBL_ARTIST.
        " SET ".
        " last_name='".addslashes($post['last_name'])."' ".
        ",first_name='".addslashes($post['first_name'])."' ".
        ",company_id='".addslashes($post['company_id'])."' ".
        ",copyright_subscriber_num='".addslashes($post['copyright_subscriber_num'])."' ".
        ",address_line1='".addslashes($post['address_line1'])."' ".
        ",address_line2='".addslashes($post['address_line2'])."' ".
        ",city='".addslashes($post['city'])."' ".
        ",title='".addslashes($post['title'])."' ".
        ",website='".addslashes($post['website'])."' ".
        ",fax='".addslashes($post['fax'])."' ".
        ",cell_phone='".addslashes($post['cell_phone'])."' ".
        ",work_phone='".addslashes($post['work_phone'])."' ".
        ",zip_code='".addslashes($post['zip_code'])."' ".
		",personal_detail='".addslashes($post['personal_detail'])."' ".
        ",country_id = '".addslashes($post['country_id'])."'".
		",preview_length = '".addslashes($post['preview_length'])."'".
        ",update_date=now() ".
        " WHERE pseudo='".$pseudo."' AND artist_name='".addslashes($post['id'])."'";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }


  function updateManagerProfile ($pseudo,$post){
    $errorFlag=false;
    if(!$this->updateSubscriberProfile($pseudo,$post)) $errorFlag=true;
    else{
      $query="UPDATE "._TBL_MANAGER.
        " SET ".
        " last_name='".addslashes($post['last_name'])."' ".
        ",first_name='".addslashes($post['first_name'])."' ".
        ",company_reg_num='".addslashes($post['company_reg_num'])."' ".
        ",manager_name='".addslashes($post['manager_name'])."' ".
        ",vat_id='".addslashes($post['vat_id'])."' ".
        ",address_line1='".addslashes($post['address_line1'])."' ".
        ",address_line2='".addslashes($post['address_line2'])."' ".
        ",city='".addslashes($post['city'])."' ".
        ",title='".addslashes($post['title'])."' ".
        ",website='".addslashes($post['web_site'])."' ".
        ",fax='".addslashes($post['fax'])."' ".
        ",cell_phone='".addslashes($post['cell_phone'])."' ".
        ",work_phone='".addslashes($post['work_phone'])."' ".
        ",zip_code='".addslashes($post['zip_code'])."' ".
        ",country_id = '".addslashes($post['country_id'])."'".
        ",preview_length = '".$post['preview_length']."'".
        ",update_date=now() ".
        " WHERE pseudo='".$pseudo."'";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    }
    if ($errorFlag) return(false) ; else return(true);
  }
  function updateArtistInfo ($pseudo,$post){
    $errorFlag=false;
    if(trim($pseudo)){
      $query="UPDATE "._TBL_ARTIST.
        " SET ".
        " image_id='".addslashes($post['image_id'])."' ".
        ",personal_detail='".addslashes($post['personal_detail'])."' ".
        ",update_date=now() ".
        " WHERE pseudo='$pseudo'";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }
  function registerNewSingle ($songID,$imgID,$priceID,$preview,$artist){
    $errorFlag=false;
    $query="insert "._TBL_SINGLE.
          " (single_id, artist_name, song_id, image_id , price_id, preview_length,creation_date,update_date,active ) ".
          " values ('".uniqid()."','".addslashes($artist)."','".addslashes($songID)."' ,'".addslashes($imgID)."' ".
          ",'".$priceID."',".$preview.", now(), now(),'Y')";
    $result=$this->db->query($query);
    if(!$result || $this->db->error()) $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }


  function createNewEvent ($pseudo,$post){
    $errorFlag=false;
    $query="insert "._TBL_EVENT.
          " (event_id,pseudo,title , description, address,how_to_access,phone,map_url,web_site ,price,pre_sales, ".
		  " drink_price,image_id,music_type,country_id, email, start_date,end_date, creation_date, active,artist_name,payed) ".
          " values ".
          " ( ".
		  " '".uniqid()."','".addslashes($pseudo)."','".addslashes($post['title'])."', '".addslashes($post['description'])."','".addslashes($post['address'])."' ".
		  ",'".addslashes($post['how_to_access'])."','".addslashes($post['phone'])."','".addslashes($post['map_url'])."','".addslashes($post['web_site'])."'".
		  ",'".addslashes($post['price'])."','".addslashes($post['pre_sales'])."','".addslashes($post['drink_price'])."','".addslashes($post['image_id'])."' ".
		  ",'".addslashes($post['music_type'])."','".addslashes($post['country_id'])."','".addslashes($post['email'])."'".
          ",'".$this->lib->dateFr2Sql($post['start_date'],'/')."' ".
          ",'".$this->lib->dateFr2Sql($post['end_date'],'/')."' ".
          ",now(), 'Y','".addslashes($post['artist_id'])."','N'".
          " )";
    $result=$this->db->query($query);
    if(!$result || $this->db->error()) $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }


	  /*
	   * Register POST file information
	   */

  function registerNewMP3 ($pseudo,$fileName='',$info,$fileSize){
    $errorFlag=false;
    if(isset($pseudo) && trim($fileName)){
      $query="REPLACE "._TBL_SONG.
            " (song_id, pseudo, file_name, album,year_id, title, artist, track, rate_id, composer,band,publisher, length, genre_id, filesize, creation_date,update_date, active ) ".
            " values ".
            " ( ".
            "	'".uniqid()."' ".
            ",'".addslashes($pseudo)."' ".
            ",'".$fileName."' ".
            ",'".addslashes(trim(str_replace('"',"",$info->tags['id3v1']['album'][0])))."' ".
            ",'".(int) $info->tags['id3v1']['year'][0]."' ".
            ",'".addslashes(trim(str_replace('"',"",$info->tags['id3v1']['title'][0])))."' ".
            ",'".addslashes(trim(str_replace('"',"",$info->tags['id3v1']['artist'][0])))."' ".
            ",'".(int) $info->tags['id3v1']['track'][0]."' ".
            ",'".round($info->avg_bit_rate)."' ".
            ",'".addslashes(trim(str_replace('"',"",$info->tags['id3v2']['composer'][0])))."' ".
            ",'".addslashes(trim(str_replace('"',"",$info->tags['id3v2']['band'][0])))."' ".
            ",'".addslashes(trim(str_replace('"',"",$info->tags['id3v2']['publisher'][0])))."' ".
            ",'".$this->lib->sec2hms($info->playing_time)."' ".
            ",'".addslashes($info->tags['id3v2']['genre'][0])."' ".
            ",'".$fileSize."' ".
            ", now()".
            ", now()".
            ",'P'".
            " )";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    }else $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }

  function registerNewMP3Preview ($pseudo,$fileName='',$info,$fileSize){
    $errorFlag=false;
    if(isset($pseudo) && trim($fileName)){
      $query="REPLACE "._TBL_SONG_PREVIEW.
            " (preview_id, pseudo, file_name, title, artist
            , rate_id, length
            , filesize, creation_date,update_date, active ) ".
            " values ".
            " ( ".
            "	'".uniqid()."' ".
            ",'".addslashes($pseudo)."' ".
            ",'".$fileName."' ".
            ",'".addslashes(trim(str_replace('"',"",$info->tags['id3v1']['title'][0])))."' ".
            ",'".addslashes(trim(str_replace('"',"",$info->tags['id3v1']['artist'][0])))."' ".
            ",'".round($info->avg_bit_rate)."' ".
            ",'".$this->lib->sec2hms($info->playing_time)."' ".
            ",'".$fileSize."' ".
            ", now()".
            ", now()".
            ",'P'".
            " )";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    }else $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }

  function registerNewMov ($pseudo,$fileName='',$info,$fileSize){
    $errorFlag=false;
    if(isset($pseudo) && trim($fileName)){
      $query="REPLACE "._TBL_VIDEO.
            " (video_id, pseudo, file_name, title, artist_name, description, length, creation_date,update_date, active ) ".
            " values ".
            " ( ".
            "	'".uniqid()."' ".
            ",'".addslashes($pseudo)."' ".
            ",'".$fileName."' ".
            ",'' ".
            ",'' ".
            ",'' ".
            ",'".$this->lib->sec2hms($info['playtime_string'])."' ".
            //",'".$fileSize."' ".
            ", now()".
            ", now()".
            ",'P'".
            " )";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    }else $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }

  function registerNewImage($pseudo,$fileName,$fileSize,$ext){
    $errorFlag=false;
    if(isset($pseudo) && trim($fileName)){
      $query="REPLACE "._TBL_IMAGE." (image_id,pseudo, description ,file_name,file_size, creation_date, active, file_type) values ".
            " ('".uniqid()."','".addslashes($pseudo)."','".addslashes($fileName)."','".addslashes($fileName)."','".$fileSize."', now(),'Y','".$ext."')";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    }else $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }
  function registerImageVideo($pseudo,$fileName,$fileSize,$id,$description, $ext){
    $errorFlag=false;
    if(isset($pseudo) && trim($fileName)){
      $query="REPLACE "._TBL_IMAGE." (image_id,pseudo, description ,file_name,file_size, creation_date, active, file_type) values ".
            " ('".$id."','".addslashes($pseudo)."','".addslashes($description)."','".addslashes($fileName)."','".$fileSize."', now(),'Y','".$ext."')";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    }else $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }

	  /*
	   * Register POST album information
	   */
  function registerNewAlbum ($id,$post,$artist){
    $errorFlag=false;
    $query="insert "._TBL_ALBUM.
          " (album_id,album_name , artist_name, description, year_id, image_id, genre_id , rate_id , publisher,price_id,preview_length,creation_date,update_date,active ) ".
          " values ".
          " ( ".
          "	'$id','".addslashes($post['album_name'])."' ".
          ",'".addslashes($artist)."' ".
          ",'".addslashes($post['description'])."' ".
          ",'".addslashes($post['year_id'])."' ".
          ",'".addslashes($post['image_id'])."' ".
          ",'".addslashes($post['genre_id'])."' ".
          ",'".addslashes($post['rate_id'])."' ".
          ",'".addslashes($post['publisher'])."' ".
          ",".$post['price_id'].
          ",".$post['preview_length'].
          ", now()".
          ", now()".
          ",'Y'".
          " )";
    $result=$this->db->query($query);
    if(!$result || $this->db->error()) $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }

	  /*
	   * Register POST album information
	   */
  function updateAlbum ($post,$artist){
    $errorFlag=false;
    if(trim($artist)){
      $query="UPDATE "._TBL_ALBUM.
            " SET ".
            " album_name='".addslashes($post['album_name'])."' ".
            ", description='".addslashes($post['description'])."' ".
            ", year_id='".addslashes($post['year_id'])."' ".
            ",image_id='".addslashes($post['image_id'])."' ".
            ", genre_id='".addslashes($post['genre_id'])."' ".
//            ", rate_id = '".addslashes($post['rate_id'])."'".
            ", publisher='".addslashes($post['publisher'])."' ".
            ",price_id= ".$post['price_id'].
            ",preview_length= ".$post['preview_length'].
            ",update_date=now() ".
            " WHERE ".
            "album_id='".$post['album_id']."'".
            " AND artist_name='".addslashes($artist)."' ";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }



  function updateImage ($post){
    $errorFlag=false;
      $query="UPDATE "._TBL_IMAGE.
        " SET description='".addslashes($post['description'])."' ".
        ",update_date=now() ".
        " WHERE image_id='".addslashes($post['image_id'])."'";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }


  function updateSubscriberProfile ($pseudo,$post){
    $errorFlag=false;
    if(trim($pseudo)){
      $query="UPDATE "._TBL_SUBSCRIBER.
        " SET ".
        " title='".$post['title']."' ".
        " ,first_name='".addslashes($post['first_name'])."' ".
        " ,last_name='".addslashes($post['last_name'])."' ".
        " ,cell_phone='".addslashes($post['cell_phone'])."' ".
        " ,work_phone='".addslashes($post['work_phone'])."' ".
        " ,home_phone='".addslashes($post['home_phone'])."' ".
        " ,country_id='".$post['country_id']."' ".
        ",update_date=now() ".
        " WHERE pseudo='$pseudo'";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }

  function createPlayerPref ($pseudo='', $post){
    $errorFlag=false;
    if(trim($pseudo) && trim($post['artist_id'])){
      $result=$this->db->query("delete from "._TBL_PLAYER_PREF." where pseudo = '$pseudo' AND artist_name='".$post['artist_id']."'");
	}
    else{
      $result=$this->db->query("delete from "._TBL_PLAYER_PREF." where pseudo = '$pseudo'");
	}

    $query="insert "._TBL_PLAYER_PREF.
          " (pseudo,artist_name , default_tab, autostart, skin, default_lang , playlist_active ,event_active,creation_date,user ) ".
          " values ".
          " ( ".
          "	'$pseudo' ".
		  ", '".addslashes($post['artist_id'])."' ".
          ",'".addslashes($post['default_tab'])."' ".
          ",'".addslashes($post['autostart'])."' ".
          ",'".addslashes($post['skin'])."' ".
          ",'".addslashes($post['default_lang'])."' ".
          ",'".addslashes($post['playlist_active'])."' ".
          ",'".addslashes($post['event_active'])."' ".
          ", now()".
		  ",'".crypt($pseudo,'GetMyVibe+10-10')."'".
          " )";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }


  function updatePref ($pseudo='', $post){
    $errorFlag=false;
    if(trim($pseudo)){
//	    		$result=$this->db->query("delete from "._TBL_SUBSCRIBER_PREF." where pseudo = '$pseudo'");
      $query="update "._TBL_SUBSCRIBER_PREF." set ".
            "counter_displayed='".addslashes($post['counter_displayed'])."' ".
            // ",report_line='".addslashes($post['report_line'])."' ".
            ",blog_active='".addslashes($post['blog_active'])."' ".
            ",space_active='".addslashes($post['space_active'])."' ".
            ",page_name='".addslashes($post['page_name'])."' ".
            ",image_id='".addslashes($post['image_id'])."' ".
            ",language_pref='".addslashes($post['language_pref'])."' ".
            ",update_date= now() ".
            " where pseudo='".$pseudo."'";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }


  function updateMusicPref ($pseudo='', $post){
    $errorFlag=false;
    if(trim($pseudo)){
      $query="update "._TBL_SUBSCRIBER_PREF." set ".
            "music_pref_frequency='".addslashes($post['music_pref_frequency'])."' ".
            ",music_pref_active='".addslashes($post['music_pref_active'])."' ".
            ",update_date= now() ".
            " where pseudo='".$pseudo."'";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }


  function updateSubscriberPref ($pseudo='', $post){
    $errorFlag=false;
    if(trim($pseudo)){
//	    		$result=$this->db->query("delete from "._TBL_SUBSCRIBER_PREF." where pseudo = '$pseudo'");
      $query="update "._TBL_SUBSCRIBER_PREF." set ".
            " nb_album='".addslashes($post['nb_album'])."' ".
            ",nb_title='".addslashes($post['nb_title'])."' ".
            ",nb_news='".addslashes($post['nb_news'])."' ".
            ",blog_active='".addslashes($post['blog_active'])."' ".
            ",space_active='".addslashes($post['space_active'])."' ".
//		            ",'".addslashes($post['report_line'])."' ".
            ",creation_date= now()) ".
            " where pseudo='".$pseudo."'";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }


  /*
   * Register POST album information
   */
  function updateSingle ($post,$artist){
    $errorFlag=false;
    if(trim($artist)){
      $query="UPDATE "._TBL_SINGLE.
            " SET ".
            " image_id='".addslashes($post['image_id'])."' ".
//            ",song_id='".addslashes($post['song_id'])."' ".
            ",price_id= ".$post['price_id'].
            ",video_id='".$post['video_id']."'".
            ",preview_length= ".$post['preview_length'].
            ",update_date=now() ".
            " WHERE ".
            " single_id='".$post['single_id']."'".
            " AND artist_name='".addslashes($artist)."' ";
      $result=$this->db->query($query);
      if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }

  /*
   * Register POST album information
   */
  function updateSong ($pseudo,$post,$artist){
    $errorFlag=false;
    if(isset($pseudo)){
        $query="UPDATE "._TBL_SONG.
              " SET ".
        " title='".addslashes($post['title'])."' ".
        " ,composer='".addslashes($post['composer'])."' ".
        " ,author='".addslashes($post['author'])."' ".
        " ,publisher='".addslashes($post['publisher'])."' ".
        " ,band='".addslashes($post['band'])."' ".
        " ,album='".addslashes($post['album'])."' ".
        " ,year_id='".$post['year_id']."' ".
        " ,isrc_code='".trim($post['isrc_code'])."' ".
        " ,track='".$post['track']."' ".
//        " ,rate_id='".$post['rate_id']."' ".
        " ,genre_id='".$post['genre_id']."' ".
        " ,year_id='".$post['year_id']."' ".
        " ,preview_id='".$post['preview_id']."' ".
        " ,artist_name='".addslashes($artist)."' ".
        " ,active='Y' ".
        ",update_date=now() ".
        " WHERE song_id='".$post['song_id']."'".
        " AND pseudo='".addslashes($pseudo)."' ";

        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }

  function updateSongPreview ($pseudo,$post,$artist){
    $errorFlag=false;
    if(isset($pseudo)){
        $query="UPDATE "._TBL_SONG_PREVIEW.
              " SET ".
        " title='".addslashes($post['title'])."' ".
        " ,artist_name='".addslashes($artist)."' ".
        " ,active='Y' ".
        ",update_date=now() ".
        " WHERE preview_id='".$post['preview_id']."'".
        " AND pseudo='".addslashes($pseudo)."' ";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }

  function updateVideo ($pseudo,$post,$artist){
    $errorFlag=false;
    if(isset($pseudo)){
        $query="UPDATE "._TBL_VIDEO.
              " SET ".
        " title='".addslashes($post['title'])."' ".
        " ,description='".addslashes($post['description'])."' ".
        " ,flv_file_name='".addslashes($post['flv_file_name'])."' ".
        " ,artist_name='".addslashes($artist)."' ".
        " ,image_id='".addslashes($post['image_id'])."' ".
        " ,length='".$this->lib->sec2hms($post['playing_time'])."' ".
        " ,active='Y' ".
        ",update_date=now() ".
        " WHERE video_id='".$post['video_id']."'".
        " AND pseudo='".addslashes($pseudo)."' ";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }

  function updateEvent ($post){
    $errorFlag=false;
    if(isset($_SESSION['PSEUDO'])){
        $query="UPDATE "._TBL_EVENT." SET ".
        " description='".addslashes($post['description'])."' ".
        ",address='".addslashes($post['address'])."' ".
        ",how_to_access='".addslashes($post['how_to_access'])."' ".
        ",map_url='".addslashes($post['map_url'])."' ".
        ",web_site='".addslashes($post['web_site'])."' ".
        ",price='".addslashes($post['price'])."' ".
        ",pre_sales='".addslashes($post['pre_sales'])."' ".
        ",drink_price='".addslashes($post['drink_price'])."' ".
        ",music_type='".addslashes($post['music_type'])."' ".
        ",country_id='".addslashes($post['country_id'])."' ".
        ",email='".addslashes($post['email'])."' ".
        ",title='".addslashes($post['title'])."' ".
        ",image_id='".addslashes($post['image_id'])."' ".
        ",start_date='".$this->lib->dateFr2Sql($post['start_date'],'/')."'".
        ",end_date='".$this->lib->dateFr2Sql($post['end_date'],'/')."'".
        ",update_date=now() ".
        " WHERE event_id='".$post['event_id']."'".
        " AND pseudo='".addslashes($_SESSION['PSEUDO'])."' ";

        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    }else return(false);
    if ($errorFlag) return(false) ; else return(true);
  }

 /*
   * Approve subscriber record
   */
  function changePartnerStatus($subscriberID, $approvalCode,$updateCode='' ){
      $query =" UPDATE  "._TBL_PARTNER.
              " set approval_status='$approvalCode' ".
              ", update_code='$updateCode' ".
              " where partner_id = '$subscriberID'";
      $result = $this->db->query($query);
      if ($this->db->affected_rows()>0) return (true);
      else return (false);
 }
  /*
   * update the counter record
   */
  function updateSpaceCounter($memberName){
      $query =" UPDATE  "._TBL_MY_VIBE." VS, "._TBL_SUBSCRIBER_PREF." SP ".
              " set display_counter= display_counter+1 ".
              " where VS.pseudo = SP.pseudo and SP.page_name = '".$memberName."'";
      $result = $this->db->query($query);
      if ($this->db->affected_rows()>0) return (true); else return (false);
 }

 function updateArtistProfileView($artist){
      $query =" UPDATE  "._TBL_ARTIST." set profile_views= profile_views+1 where artist_name = '".$artist."'";
      $result = $this->db->query($query);
      if ($this->db->affected_rows()>0) return (true);   else return (false);
 }


  /*
   * List album detail
   */
  function retrieveAlbumDetail($albumID='',$artist){
    $query= "select alb.* ".
            " from ".
            _TBL_ALBUM." alb, ".
            _TBL_ARTIST." art ".
            " where ".
            " alb.artist_name = art.artist_name ".
            " AND alb.album_id='$albumID' ".
            " AND art.artist_name = '$artist'";
    return ($this->fetchData($query));
  }

  /*
   * List single detail
   */
  function retrieveSingleDetail($singleID='',$artist){
    $query= "select sing.*, song.title from "._TBL_SINGLE." sing, "._TBL_ARTIST." art , "._TBL_SONG." song ".
			" where art.artist_name = sing.artist_name  AND sing.song_id=song.song_id AND sing.single_id='$singleID'  AND song.active='Y' AND art.artist_name = '$artist'";
    return ($this->fetchData($query));
  }

  /*
   * List single detail
   */
  function retrieveSongDetail($varID,$pseudo){ $query= "select f.*  from "._TBL_SONG." f where  f.pseudo = '$pseudo'  AND f.song_id='$varID' "; return ($this->fetchData($query));    }
  function retrieveSongPreviewDetail($varID,$pseudo){ $query= "select f.*  from "._TBL_SONG_PREVIEW." f where  f.pseudo = '$pseudo'  AND f.preview_id='$varID' "; return ($this->fetchData($query));    }

  function retrieveVideoDetail($varID,$pseudo){ $query= "select *  from "._TBL_VIDEO." where  pseudo = '$pseudo'  AND video_id='$varID' "; return ($this->fetchData($query));    }


  function retrieveImageArtist($varID,$artist){
    $query= "select f.* from "._TBL_IMAGE." f, "._TBL_ARTIST." art ".
            " where f.pseudo = art.pseudo AND f.image_id='$varID' AND art.artist_name = '$artist'"; return ($this->fetchData($query));
  }

  function retrieveImagePseudo($varID){
    $query= "select f.* from "._TBL_IMAGE." f where f.image_id='$varID' "; return ($this->fetchData($query));
  }

  function getManagerArtistList($pseudo){
    $query= "select art.artist_name, art.first_name, art.last_name, art.active from "._TBL_SUBSCRIBER." s, "._TBL_ARTIST." art ".
            " where s.pseudo = art.pseudo AND s.pseudo = '$pseudo'"; return ($this->fetchData($query));
  }
  function getManagerValidArtist($pseudo){
    $query= "select art.artist_name id, concat(art.first_name,' ', art.last_name,' (',art.artist_name,')') description from "._TBL_SUBSCRIBER." s, "._TBL_ARTIST." art ".
            " where s.pseudo = art.pseudo AND s.pseudo = '$pseudo' AND art.active='Y'"; return ($this->fetchData($query));
  }

    function retrieveArtistsWithMusic($limit=0,$sort='',$start=''){
        $query="select distinct a.artist_name
            , (select count(alb.album_id) from "._TBL_ALBUM." alb where alb.artist_name = a.artist_name AND alb.active='Y'
             AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )
            ) nb_album
            , (select count(s.single_id) from "._TBL_SINGLE." s where s.artist_name = a.artist_name AND s.active='Y') nb_single
            ,date_format(a.creation_date,'%d/%m/%Y') creation_date, c.description country
            from "._TBL_ARTIST." a,"._TBL_SUBSCRIBER." s, "._TBL_COUNTRY." c
            where a.pseudo=s.pseudo AND a.country_id=c.country_id AND a.active='Y' AND s.active='Y' ";
        if(trim($start)) $query.=" AND a.artist_name REGEXP '^".$start."'"; // Starts with A
        $query.=" HAVING nb_single>0 OR nb_album >0 ";
        if(trim($sort)){
            if($sort=='date') $query.="ORDER by a.creation_date DESC ";
            else if($sort=='counter') $query.="ORDER by nb_album DESC, nb_single DESC ";
        }
        if ($limit>0) $query.=" LIMIT 0,$limit ";
        return ($this->fetchData($query));
    }

    function retrieveArtistsWithMusicByLabel($limit=0,$sort='',$label='',$start=NULL){
        $query="select distinct a.artist_name, m.manager_name, m.website, m.logo_url
            , (select count(alb.album_id) from "._TBL_ALBUM." alb where alb.artist_name = a.artist_name AND alb.active='Y'
             AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )
            ) nb_album
            , (select count(s.single_id) from "._TBL_SINGLE." s where s.artist_name = a.artist_name AND s.active='Y') nb_single
            ,date_format(a.creation_date,'%d/%m/%Y') creation_date, c.description country
            from "._TBL_ARTIST." a,"._TBL_MANAGER." m, "._TBL_COUNTRY." c
            where a.pseudo=m.pseudo AND a.country_id=c.country_id   ";
        if(trim($label)) $query.=" AND m.manager_name = '".$label."'";
		if(trim($start)) $query.=" AND a.artist_name REGEXP '^".$start."'"; // Starts with A
        $query.=" AND a.active='Y' AND m.active='Y' HAVING nb_single>0 OR nb_album >0 ";
        if(trim($sort)){
            if($sort=='date') $query.="ORDER by a.creation_date DESC ";
            else if($sort=='counter') $query.="ORDER by nb_album DESC, nb_single DESC ";
        }
        if ($limit>0) $query.=" LIMIT 0,$limit ";
        return ($this->fetchData($query));
    }

    function retrieveLabelsWithArtist($limit=0,$sort='',$start=''){
        $query="select distinct m.manager_name
            , (select count(a.artist_name)
			from "._TBL_ARTIST." a
			where m.pseudo = a.pseudo
			and (
				a.artist_name in (
					select distinct artist_name from "._TBL_ALBUM." alb where alb.artist_name = a.artist_name AND alb.active='Y'
					AND (alb.release_date <= NOW() OR alb.release_date IS NULL  )
				)
				or  a.artist_name in (
					select distinct
						artist_name from "._TBL_SINGLE." s where s.artist_name = a.artist_name AND s.active='Y'
					)
				)
			)
			nb_artist
            ,date_format(m.creation_date,'%d/%m/%Y') creation_date, c.description country
			from "._TBL_MANAGER." m, "._TBL_SUBSCRIBER." s, "._TBL_COUNTRY." c
            where m.pseudo=s.pseudo AND m.country_id=c.country_id AND m.active='Y' AND s.active='Y' ";
        if(trim($start)) $query.=" AND m.manager_name REGEXP '^".$start."'"; // Starts with A
        $query.=" HAVING nb_artist>0 ";
        if(trim($sort)){
            if($sort=='date') $query.="ORDER by m.creation_date DESC ";
            else if($sort=='counter') $query.="ORDER by nb_artist DESC ";
        }
        if ($limit>0) $query.=" LIMIT 0,$limit ";
        return ($this->fetchData($query));
    }

	/*
     * create a list of single for a given album
     */
	function createAlbumPlaylist($varID=''){
		if(isset($varID)){
      $query= "select * from ( select ".
              " CONCAT('"._SITE."/listen/song/',song.song_id) location ".
              " ,art.artist_name creator".
              ", song.title, song.song_id, song.track ".
              " from ".
              _TBL_ALBUM." alb, ".
              _TBL_ARTIST." art, ".
              _TBL_SONG." song, ".
              _TBL_ALBUM_SONG." sa ".
              " where ".
              " art.artist_name = alb.artist_name ".
              " and alb.album_id = sa.album_id ".
              " AND sa.song_id = song.song_id ".
      		  " AND alb.album_id='$varID' ".
       		  " AND art.active='Y' AND art.approval_status='A' AND song.active='Y' AND alb.active ='Y' ) as album ORDER BY track ASC";
      return ($this->fetchData($query));
		}
	}

	function getPlayListItems($varID){
		if(isset($varID)){
      $query="select ".
             " CONCAT('"._WEBSITE."/listen/single/',sing.single_id) location ,art.artist_name creator, song.title ".
             " from ".
             _TBL_ALBUM." alb, "._TBL_ARTIST." art,"._TBL_SINGLE." sing,"._TBL_SONG." song, "._TBL_PLAYLIST." pl,"._TBL_PLAYLIST_ITEM." pli,"._TBL_ALBUM_SONG." sa ".
             " where ".
             " pl.playlist_id = pli.playlist_id AND pli.item_id = alb.album_id AND alb.artist_name = art.artist_name AND alb.album_id = sa.album_id ".
             " AND sa.song_id = song.song_id AND pli.item_type='album' and pl.playlist_id ='$varID' ".
       		  " AND art.active='Y' AND art.approval_status='A'  AND song.active='Y' AND sing.active='Y' AND alb.active ='Y' ".
       		  " UNION ".
       		  "select ".
            " CONCAT('"._WEBSITE."/listen/single/',sing.single_id) location ,art.artist_name creator, song.title ".
            " from ".
            _TBL_ALBUM." alb, "._TBL_ARTIST." art, "._TBL_SINGLE." sing, "._TBL_SONG." song, "._TBL_PLAYLIST." pl,"._TBL_PLAYLIST_ITEM." pli,"._TBL_ALBUM_SONG." sa ".
            " where ".
            " pl.playlist_id = pli.playlist_id AND pli.item_id = sing.single_id AND sing.song_id = song.song_id AND sing.artist_name = art.artist_name ".
      		  " AND pl.playlist_id='$varID' ".
      		  " AND pli.item_type='single' ".
      		  " AND art.active='Y' AND art.approval_status='A'  AND song.active='Y' AND sing.active='Y' AND alb.active ='Y' ".
      		  " GROUP BY location";
      return ($this->fetchData($query));
		}
	}

	function getPlayListDetail($varID){
		if(isset($varID)){
      $query="select ".
             " distinct alb.album_id id,art.artist_name, alb.album_name title, alb.genre_id, gen.description genre_desc, alb.price_id, pri.description price_desc, pli.item_type ".
             " from ".
             _TBL_ALBUM." alb, "._TBL_ARTIST." art, "._TBL_PLAYLIST." pl,"._TBL_PLAYLIST_ITEM." pli,"._TBL_GENRE." gen ,"._TBL_PRICE." pri ".
             " where ".
             " pl.playlist_id = pli.playlist_id AND pli.item_id = alb.album_id and alb.genre_id = gen.genre_id AND alb.artist_name = art.artist_name  ".
             " AND alb.price_id=pri.price_id AND pli.item_type='album' and pl.playlist_id ='$varID' ".
       		  " AND art.active='Y' AND art.approval_status='A' AND alb.active ='Y'".
       		  " UNION ".
       		  "select ".
             " sing.single_id id,art.artist_name, song.title, song.genre_id, gen.description genre_desc, sing.price_id, pri.description price_desc, pli.item_type".
             " from ".
             _TBL_SINGLE." sing, "._TBL_SONG." song,"._TBL_ARTIST." art, "._TBL_PLAYLIST." pl,"._TBL_PLAYLIST_ITEM." pli,"._TBL_GENRE." gen ,"._TBL_PRICE." pri ".
             " where ".
             " pl.playlist_id = pli.playlist_id AND pli.item_id = sing.single_id and sing.song_id=song.song_id AND song.genre_id = gen.genre_id AND sing.artist_name = art.artist_name  ".
             " AND sing.price_id=pri.price_id  AND pli.item_type='single' and pl.playlist_id ='$varID' ".
       		  " AND art.active='Y' AND art.approval_status='A' AND song.active ='Y' AND sing.active ='Y'";
      return ($this->fetchData($query));
		}
	}

	function retrieveArtistPref($pseudo,$artist=''){
		if(trim($pseudo)){
			$query="select nb_file,nb_title,nb_album, report_line from "._TBL_SUBSCRIBER_PREF." where pseudo='$pseudo'";
			return ($this->fetchData($query));
		}
	}
	function retrieveSubcriberPref($pseudo){
		if(trim($pseudo)){
			$query="select nb_news,nb_title,nb_album,nb_file,report_line,blog_active,counter_displayed,space_active,language_pref,page_name,image_id from "._TBL_SUBSCRIBER_PREF." where pseudo='$pseudo'";
			return ($this->fetchData($query));
		}
	}

	function retrievePlayerPref($pseudo='',$artist=''){
		if(trim($pseudo)){
			$query="select *  from "._TBL_PLAYER_PREF." where pseudo='$pseudo'";
			if(trim($artist)) $query.=" AND artist_name='$artist'";
			return ($this->fetchData($query));
		}
	}
	/* retrieve config player for player config data */
	function getPlayerPref($artist='',$pseudo=''){
		if(trim($pseudo)){
			$query="select default_tab,default_lang, event_active, user ".
			", (case autostart when 'Y' then 'true' else 'false' END ) autostart  ,skin,playlist_active as playlist from "._TBL_PLAYER_PREF." where pseudo='$pseudo'";
		}
		else if(trim($artist)){
			$query="select  default_tab,default_lang, event_active,user ".
			", (case autostart when 'Y' then 'true' else 'false' END ) autostart ,skin,playlist_active as playlist from "._TBL_PLAYER_PREF." where artist_name='$artist'";
		}
			return ($this->fetchData($query));
	}


	function prefPageNameExist($pseudo,$var){
		$result = $this->db->query("SELECT count(page_name) FROM "._TBL_SUBSCRIBER_PREF." where page_name='$var' AND pseudo!='$pseudo'");
		if ($this->db->error()) return(false);
		else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true); else return (false);}
	}

	function retrieveMyVibe($pseudo){
		if(trim($pseudo)){ $query="select * from "._TBL_MY_VIBE." where pseudo='$pseudo'"; return ($this->fetchData($query)); }
	}

	function retrieveMyVibePlaylist($pseudo){
		if(trim($pseudo)){ $query="select * from "._TBL_PLAYLIST." P where pseudo='$pseudo'"; return ($this->fetchData($query));}
	}
	function getPlaylistWithData($pseudo){
		if(trim($pseudo)){ $query="select * from "._TBL_PLAYLIST." P where pseudo='$pseudo' and playlist_id in (select playlist_id from "._TBL_PLAYLIST_ITEM.") "; return ($this->fetchData($query));}
	}
	function retrieveMyVibeFriend($pseudo){
		if(trim($pseudo)){
			$query="select F.requestor pseudo  from "._TBL_FRIENDS." F,"._TBL_SUBSCRIBER." S where F.requestor=S.pseudo AND F.pseudo='$pseudo' AND F.approved='Y' AND S.active='Y'".
				" UNION ".
				"select F.requestor pseudo from "._TBL_FRIENDS." F,"._TBL_SUBSCRIBER." S where F.requestor=S.pseudo  AND F.pseudo='$pseudo' AND F.approved='Y' AND S.active='Y'".
				" UNION ".
				"select F.pseudo  from "._TBL_FRIENDS." F,"._TBL_SUBSCRIBER." S where F.pseudo=S.pseudo AND F.requestor='$pseudo' AND F.approved='Y' AND S.active='Y'".
				" UNION ".
				"select F.pseudo from "._TBL_FRIENDS." F,"._TBL_SUBSCRIBER." S where F.pseudo=S.pseudo AND F.requestor='$pseudo' AND F.approved='Y' AND S.active='Y'";
			return ($this->fetchData($query));
		}
	}

	function retrieveMyVibeComments($varID){
		if(trim($varID)){
			$query="select C.*, P.image_id, date_format(C.creation_date, '%d/%m/%Y , %H:%i') date, ".
			" (select CONCAT(I.image_id,'/',I.file_type) from "._TBL_IMAGE." I where I.image_id = P.image_id ) image_id ".
			" from "._TBL_COMMENT_PAGE." C,"._TBL_SUBSCRIBER_PREF." P where P.pseudo=C.pseudo AND vibe_id='$varID'  order by C.creation_date DESC";
			return ($this->fetchData($query));
		}
	}

	function getBlogComment($blogId){
		if(trim($blogId)){
			$query="select C.*, date_format(C.creation_date,'%d-%m-%Y , %h:%i') date,P.image_id, (select file_name from "._TBL_IMAGE." I where I.image_id = P.image_id ) image_name from "._TBL_BLOG_COMMENT." C,"._TBL_SUBSCRIBER." S,"._TBL_SUBSCRIBER_PREF." P where C.pseudo= S.pseudo AND S.pseudo=P.pseudo AND C.blog_id='$blogId' ORDER by creation_date DESC";
			return ($this->fetchData($query));
		}
	}

	function countBlogComments($varID){
		if(trim($varID)){
			$result = $this->db->query("SELECT count(blog_id) FROM "._TBL_BLOG_COMMENT." where blog_id='$varID'");
			if ($this->db->error()) return(false);else {list($item) = mysql_fetch_row($result); return ($item);}
		}
	}

	function retrieveMyVibeDetail($pageName){
		if(trim($pageName)){
			$query="select V.*,C.description country, P.page_name,P.blog_active,P.counter_displayed, DATE_FORMAT(V.creation_date,'%d-%m-%Y , %H:%i') creation_date,DATE_FORMAT(S.creation_date,'%d-%m-%Y') member_date ".
            ", (select CONCAT(I.image_id,'/',I.file_type) from "._TBL_IMAGE." I where I.image_id = V.image_id ) image_id ".
            ", (select CONCAT(I.image_id,'/',I.file_type) from "._TBL_IMAGE." I where I.image_id = V.background_url ) image_background ".
            " from "._TBL_MY_VIBE." V,"._TBL_SUBSCRIBER." S,"._TBL_SUBSCRIBER_PREF." P ,"._TBL_COUNTRY." C ".
            "where V.pseudo=S.pseudo AND S.pseudo=P.pseudo AND C.country_id = S.country_id AND P.page_name='$pageName' ".
            " AND P.space_active='Y' AND S.active='Y'"  ;
			return ($this->fetchData($query));
		}
	}
	function retrieveMyBlogDetail($pageName,$blogId=''){
		if(trim($pageName)){
			$query="select B.*,P.page_name,P.space_active, DATE_FORMAT(B.update_date, '%d-%m-%Y , %H:%i') creation_date ".
            ", (select CONCAT(I.image_id,'/',I.file_type) from "._TBL_IMAGE." I where I.image_id = P.image_id ) image_id ".
            ",(select count(comment_id) from "._TBL_BLOG_COMMENT." C where C.blog_id = B.blog_id ) nbComment ".
            " from "._TBL_BLOG." B,"._TBL_SUBSCRIBER." S,"._TBL_SUBSCRIBER_PREF." P ".
            "where B.pseudo=S.pseudo AND S.pseudo=P.pseudo AND P.page_name='$pageName' ";
      if(trim($blogId)) $query.=" AND B.blog_id='$blogId'";
      $query.=" AND P.blog_active='Y' AND S.active='Y' ORDER by B.creation_date DESC, B.update_date DESC"  ; return ($this->fetchData($query));
		}
	}

	function retrieveBlogList($limit=0,$sort='',$start=''){
		$query="select distinct P.page_name,C.description country, S.pseudo ".
			", (select CONCAT(I.image_id,'/',I.file_type) from "._TBL_IMAGE." I where I.image_id = P.image_id ) image_id ".
		" from "._TBL_BLOG." B,"._TBL_SUBSCRIBER." S,"._TBL_SUBSCRIBER_PREF." P, "._TBL_COUNTRY." C ".
		"where B.pseudo=S.pseudo AND S.pseudo=P.pseudo AND S.country_id = C.country_id AND P.blog_active='Y' AND S.active='Y' ";

        if(trim($start)) $query.=" AND P.page_name REGEXP '^".$start."'"; // Starts with A
		if(trim($sort)){
            if($sort=='date') $query.="ORDER by B.creation_date DESC ";
            else if($sort=='counter') $query.="ORDER by B.display_counter DESC, B.update_date DESC ";
        }
		if ($limit>0) $query.=" LIMIT 0,$limit ";

        //		"group by P.page_name ".
//		"ORDER by B.creation_date DESC, B.update_date DESC ";
	//	if ($limit>0) $query.=" LIMIT 0,$limit ";
		return ($this->fetchData($query));
	}
	function retrieveLastBlog($limit=0,$memberName=''){
		$query="select distinct P.page_name,S.pseudo, B.title, DATE_FORMAT(B.update_date, '%d-%m-%Y , %H:%i') creation_date ".
		" from "._TBL_BLOG." B,"._TBL_SUBSCRIBER." S,"._TBL_SUBSCRIBER_PREF." P ".
		"where B.pseudo=S.pseudo AND S.pseudo=P.pseudo AND P.blog_active='Y' AND S.active='Y' ";
		if(trim($memberName) && isset($memberName)) $query.=" AND P.page_name='$memberName' ";
//		"group by P.page_name ".
		$query.=" ORDER by B.creation_date DESC";
		if ($limit>0) $query.=" LIMIT 0,$limit ";
		return ($this->fetchData($query));
	}

	function retrieveSpaceList($limit=0,$sort='',$start=''){
		$query="select distinct P.page_name, date_format(P.update_date,'%d/%m/%Y') last_update, S.pseudo, C.description country, display_counter counter, ".
			" (select CONCAT(I.image_id,'/',I.file_type) from "._TBL_IMAGE." I where I.image_id = B.image_id ) image_id ".
		" from "._TBL_MY_VIBE." B,"._TBL_SUBSCRIBER." S,"._TBL_SUBSCRIBER_PREF." P,"._TBL_COUNTRY." C  ".
		"where B.pseudo=S.pseudo AND S.pseudo=P.pseudo AND S.country_id=C.country_id AND P.space_active='Y' AND S.active='Y' ";
//		"group by P.page_name ".
//		"ORDER by B.display_counter DESC, B.update_date DESC ";
        if(trim($start)) $query.=" AND P.page_name REGEXP '^".$start."'"; // Starts with A
		if(trim($sort)){
            if($sort=='date') $query.="ORDER by B.creation_date DESC ";
            else if($sort=='counter') $query.="ORDER by B.display_counter DESC, B.update_date DESC ";
        }
		if ($limit>0) $query.=" LIMIT 0,$limit ";
		return ($this->fetchData($query));
	}


	function retrieveMyBlog($pseudo,$blogId){
		if(trim($pseudo) && trim($blogId)){
			$query="select * from "._TBL_BLOG." where pseudo='$pseudo' AND blog_id='$blogId'"; return ($this->fetchData($query));
		}
	}

	function createMySpace ($pseudo,$post){
		$errorFlag=false;$allowed='<p><a><i><b><img>';
		$query="insert "._TBL_MY_VIBE." (vibe_id,pseudo,title,movies,music,sport,tv_show,books,image_id,background_url,profile_text,sales_enable,playlist_enable,private, active,creation_date,update_date ) ".
		        " values ".
		        " ('".uniqid()."','".$pseudo."','".strip_tags(addslashes($post['title']))."','".strip_tags(addslashes($post['movies']),$allowed)."','".strip_tags(addslashes($post['music']))."','".strip_tags(addslashes($post['sport']),$allowed)."' ".
		        ",'".strip_tags(addslashes($post['tv_show']),$allowed)."','".strip_tags(addslashes($post['books']),$allowed)."','".addslashes($post['image_id'])."' ,'".strip_tags(addslashes($post['background_url']))."'".
		        ",'".addslashes($post['profile_text'])."','".$post['sales_enable']."','".$post['playlist_enable']."','".$post['private']."','".$post['active']."',now(),now())";
		$result=$this->db->query($query); if(!$result || $this->db->error()) $errorFlag=true; if ($errorFlag) return(false) ; else return(true);
	}
	function updateMySpace ($pseudo,$post){
		$errorFlag=false;$allowed='<p><a><i><b>';
		$query="update "._TBL_MY_VIBE.
						" set title='".strip_tags(addslashes($post['title']))."', sales_enable='".$post['sales_enable']."',playlist_enable='".$post['playlist_enable']."',private='".$post['private']."' ".
						",movies = '".strip_tags(addslashes($post['movies']),$allowed)."',music='".strip_tags(addslashes($post['music']),$allowed)."', background_url='".strip_tags(addslashes($post['background_url']))."' ".
						",tv_show='".strip_tags(addslashes($post['tv_show']),$allowed)."',books='".strip_tags(addslashes($post['books']),$allowed)."' ,sport='".strip_tags(addslashes($post['sport']),$allowed)."'".
						",profile_text='".addslashes($post['profile_text'])."',active='".$post['active']."',image_id='".$post['image_id']."',update_date=now()".
						" where vibe_id = '".$post['vibe_id']."' and pseudo='".$pseudo."' ";
		$result=$this->db->query($query); if(!$result || $this->db->error()) $errorFlag=true; if ($errorFlag) return(false) ; else return(true);
	}

	function myVibeExist($var){
	  $result = $this->db->query("SELECT count(pseudo) FROM "._TBL_MY_VIBE." where pseudo='$var'");
    if ($this->db->error()) return(false);
    else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true); else return (false);}
	}

	/*
	 * register the order in DB
	 */
	function registerOrder($pseudo,$id,$var){
//		echo "id => register order";
		$eFlag=false;
		if($var['qty']>0 && $var['totalPrice']>0){ $result=$this->regOrderHeader($pseudo,$id,$var['qty'],$var['totalPrice'],$payment);}
		if($result)	$result=$this->regOrderLines($pseudo,$id,$var); else	$eFlag=true;
		if(!$eFlag) return(true); else return(false);
	}
	function isSongFree($itemID){
	  $result=$this->db->query("SELECT count(single_id) FROM "._TBL_SINGLE." where single_id='$itemID' AND price_id='0.00'");
    if ($this->db->error()) return(false);
    else{list($item) = mysql_fetch_row($result);if(isset($item) && $item >0) return (true);else return (false);}
	}
	function isAlbumFree($itemID){
	  $result=$this->db->query("SELECT count(album_id) FROM "._TBL_ALBUM." where album_id='$itemID' AND price_id='0.00'");
    if ($this->db->error()) return(false);
    else{list($item) = mysql_fetch_row($result);if(isset($item) && $item >0) return (true);else return (false);}
	}
	function registerFreeOrderDownload($pseudo,$id,$var){
//		echo "id => register order";
//		$var['qty']=1;$var['totalPrice']=0;
		$eFlag=false;
		if($var['qty']==1 && $var['totalPrice']==0){ $result=$this->regOrderHeader($pseudo,$id,$var['qty'],$var['totalPrice'],$payment);}
		if($result) {if(!$this->regOrderLines($pseudo,$id,$var)) $eFlag=true; } else	$eFlag=true;
		if(!$eFlag) return(true); else return(false);
	}
	function regOrderHeader($pseudo,$id, $qty,$total){
		$eFlag=false;
		$query="REPLACE "._TBL_ORDER." (pseudo,order_id,nb_item, amount, transaction_date,payment_status ) VALUES ('".$pseudo."','".$id."', '".$qty."', '".$total."',now(),'P')";
		$result=$this->db->query($query); if(!$eFlag) return(true); else return(false);
	}

	function regOrderLines($pseudo,$id,$var){
		$query = "INSERT "._TBL_ORDER_LINE." (pseudo,order_id,item_type,item_id,price,vat_rate, transaction_date ) VALUES ";
		foreach($var['data'] as $index=>$value){
			if (isset($lines)) $lines.=",( "; else $lines="( ";
			$lines.="'".$pseudo."','".$id."','".$value['item_type']."'";
			if ($value['item_type'] =='album') $lines.=", '".$value['album_id']."'";else $lines.=", '".$value['single_id']."'";
			$lines.=", '".$value['price_value']."','"._VAT_RATE."', now())";
		}
		$query.=$lines; $result=$this->db->query($query); if(!$eFlag) return(true); else return(false);
	}
	function confirmPaymentOrder($paymentType,$status,$orderNum,$partnerToken,$partnerTrxID,$amount,$fees,$currency,$firstName,$lastName,$email,$countryCode,$countryDesc){
		if($this->paymentExist($orderNum,$partnerTrxID)) return(true);
		else{
			$query = "REPLACE "._TBL_PAYMENT_AUDIT."(payment_type_id,payment_status,order_id,partner_token,partner_trx_id,amount,payment_fees,currency_code,customer_first_name, ".
					" customer_last_name,customer_email,country_code,country_description,transaction_date) VALUES ".
					"('$paymentType','$status','$orderNum','$partnerToken','$partnerTrxID','$amount','$fees','$currency','$firstName','$lastName','$email','$countryCode','$countryDesc',now())";
			$result=$this->db->query($query); if($result && $this->db->affected_rows()>0) return(true); else return(false);
		}
	}

	function paymentExist($var,$partnerTrxID){
	  $result = $this->db->query("SELECT count(order_id) FROM "._TBL_PAYMENT_AUDIT." where order_id='$var' AND partner_token='$partnerTrxID' and payment_status='Valid'");
    if ($this->db->error()) return(false);
    else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true); else return (false);}
	}

	/*
	 * display the list of item to download
	 */
	function retrieveAlbumDownload($pseudo){
		if(isset($pseudo)){
      $query= "select alb.album_name, alb.artist_name, date_format(ol.transaction_date, '%d/%m/%Y') date, ol.* ".
              " from "._TBL_ORDER_LINE." ol,"._TBL_ORDER." o, "._TBL_ALBUM." alb, "._TBL_ARTIST." art, "._TBL_PAYMENT_AUDIT." p".
              " where ol.order_id = o.order_id AND ol.item_type='album' AND ol.item_id=alb.album_id AND alb.artist_name=art.artist_name AND  o.order_id=p.order_id AND ol.pseudo = '$pseudo' ".
      		    " AND p.payment_status='Valid' AND ol.downloaded NOT IN ('Y') ";
      return ($this->fetchData($query));
		}
	}

	function retrieveSingleDownload($pseudo){
		if(isset($pseudo)){
      $query= "select song.title, sing.artist_name , date_format(ol.transaction_date, '%d/%m/%Y') date, ol.* ".
              " from "._TBL_ORDER_LINE." ol, "._TBL_ORDER." o, "._TBL_SINGLE." sing , "._TBL_SONG." song ,"._TBL_PAYMENT_AUDIT." p".
              " where ol.order_id = o.order_id AND ol.item_type='single' AND ol.item_id=sing.single_id AND sing.song_id = song.song_id AND o.order_id=p.order_id AND ol.pseudo = '$pseudo' ".
      		    " AND p.payment_status='Valid' AND (ol.downloaded not in ('Y') OR ol.downloaded is null ) ";
      return ($this->fetchData($query));
		}
	}

	/*
	 * function retrive the list of files to download
	 */
	function getSingleFileDownload($singleId,$orderID){
		$query= "SELECT distinct sing.single_id, song.file_name,song.title,song.pseudo  ".
      				" ,(select file_name from "._TBL_IMAGE." f2 where f2.image_id= sing.image_id ) image_name".
              " FROM "._TBL_ORDER." o ,"._TBL_ORDER_LINE." ol , "._TBL_SINGLE." sing , "._TBL_SONG." song, "._TBL_PAYMENT_AUDIT." p ".
              " where o.order_id = ol.order_id AND ol.item_id =sing.single_id AND sing.song_id = song.song_id AND o.order_id=p.order_id  AND ol.item_type='single' ".
			  " AND sing.single_id='$singleId' AND o.order_id= '$orderID' ".
              "  AND song.active='Y' AND ol.downloaded NOT IN ('Y') AND p.payment_status='Valid' LIMIT 0,1";
      return ($this->fetchData($query));
	}

	function getAlbumFileDownload($albumId,$orderID){
		$query= "SELECT song.song_id file_id, song.file_name,song.pseudo, alb.album_name  ".
            " FROM "._TBL_ORDER." o ,"._TBL_ORDER_LINE." ol , "._TBL_PAYMENT_AUDIT." p, "._TBL_ALBUM." alb ,"._TBL_ALBUM_SONG." sa, "._TBL_SONG." song ".
              " where ".
              " o.order_id= ol.order_id AND ol.item_id = alb.album_id AND alb.album_id = sa.album_id AND sa.song_id = song.song_id and ".
              " ol.item_type='album' AND sa.album_id='$albumId' AND o.order_id  = '$orderID'".
              " AND p.payment_status='Valid' AND ol.downloaded NOT IN ('Y')".
					// Adding the image name for this album
              " UNION ".
							" select f2.image_id, f2.file_name, f2.pseudo, alb2.album_name ".
							" FROM "._TBL_ORDER." o2 ,"._TBL_ORDER_LINE." ol2 , "._TBL_ALBUM." alb2, "._TBL_IMAGE." f2 ".
              " WHERE ".
              " o2.order_id= ol2.order_id AND ol2.item_id = alb2.album_id AND alb2.image_id = f2.image_id AND ol2.item_type='album' ".
              " AND alb2.album_id='$albumId' AND o2.order_id  = '$orderID' AND ol2.downloaded NOT IN ('Y')";
      return ($this->fetchData($query));
	}


	function setOrderLineDownloaded($pseudo,$orderID,$itemID,$itemType){
  	$query=	"UPDATE "._TBL_ORDER_LINE." SET downloaded='Y',downloaded_date=now() ".
            " WHERE pseudo='".$pseudo."' AND item_id='".$itemID."' and item_type='".$itemType."' AND order_id='".$orderID."' ";
     $this->db->query($query); if($this->db->error()) return (false); else return (true);
	}


	function registerNewCompany ($post){
		$errorFlag=false;
		$query="insert "._TBL_COPYRIGHT_COMP.
						" (company_id ,company_name , revenue_rate,address_line1 , address_line2 , city , zip_code ".
						" , country_id  , contact_name  ,email, phone ,fax ,creation_date,update_date,active ) ".
		        " values ".
		        " ('".addslashes($post['code'])."','".addslashes($post['company_name'])."','".$post['revenue_rate']."','".addslashes($post['address_line1'])."' ".
		        ", '".addslashes($post['address_line2'])."','".addslashes($post['city'])."','".addslashes($post['zip_code'])."' ".
		        ", '".addslashes($post['country_id'])."','".addslashes($post['contact_name'])."','".addslashes($post['email'])."' ".
		        ", '".addslashes($post['phone'])."','".addslashes($post['fax'])."', now(),now(),'Y')";
		$result=$this->db->query($query); if(!$result || $this->db->error()) $errorFlag=true; if ($errorFlag) return(false) ; else return(true);
	}

	function updateCompany ($post){
		$errorFlag=false;
		$query="update "._TBL_COPYRIGHT_COMP.
						" set ".
						" company_name = '".addslashes($post['company_name'])."',email = '".addslashes($post['email'])."' ".
						",  address_line1 = '".addslashes($post['address_line1'])."', address_line2 = '".addslashes($post['address_line2'])."' ".
						", city = '".addslashes($post['city'])."', zip_code = '".addslashes($post['zip_code'])."' ".
						", country_id  = '".addslashes($post['country_id'])."', contact_name  = '".addslashes($post['contact_name'])."' ".
						", phone = '".addslashes($post['phone'])."',fax = '".addslashes($post['fax'])."',revenue_rate = '".$post['revenue_rate']."',update_date =now() ".
		        " where company_id = '".$post['code']."'";
		$result=$this->db->query($query);
		if(!$result || $this->db->error()) $errorFlag=true; if ($errorFlag) return(false) ; else return(true);
	}

	function listenSingle($id,$pseudo=''){
		if(isset($id)){
			$query="insert "._TBL_STAT_SINGLE." (single_id, pseudo,date,time) values ('$id','$pseudo',now(),now())"; $this->db->query($query);
		}
	}
	function listenSong($id,$pseudo=''){
		if(isset($id)){
			$query="insert "._TBL_STAT_SONG." (song_id, pseudo,date,time) values ('$id','$pseudo',now(),now())"; $this->db->query($query);
		}
	}

	function viewVideo($id,$pseudo=''){
		if(isset($id)){
			$query="insert "._TBL_STAT_VIDEO." (video_id, pseudo,date,time) values ('$id','$pseudo',now(),now())"; $this->db->query($query);
		}
	}

	function listenPlaylist($id){
		if(isset($id)){
			$query="update "._TBL_PLAYLIST." set listened = listened +1 where playlist_id='$id'"; $this->db->query($query);
		}
	}
		function listenAlbum($id,$pseudo=''){
		if(isset($id) ){
			$query="insert "._TBL_STAT_ALBUM." (album_id, pseudo,date,time) values ('$id','$pseudo',now(),now())"; $this->db->query($query);
		}
	}





	function searchString($type, $string){
		switch($type){
			case 'ALBUM':
				$query= "SELECT  album_id item_id, art.pseudo,album_name description,alb.image_name ".
					", alb.genre_id , gen.description genre_description,price_id price_value, price_description ,alb.nb_songs, alb.album_length length".
					" from "._TBL_ALBUM." alb , "._TBL_ARTIST." art , "._TBL_GENRE." gen ,"._TBL_IMAGE." gen ".
					" WHERE alb.pseudo = art.pseudo AND alb.genre_id = gen.genre_id".
					" AND ( alb.artist_name like '%$string%' OR alb.album_name like '%$string%'OR alb.description like '%$string%' OR gen.description like '%$string%' ) ";
					" AND art.active='Y' AND art.approval_status='A' AND gen.active ='Y' AND alb.active ='Y'";
			break;

			case "SINGLE":
				$query="SELECT sing.single_id item_id,art.pseudo,S.title, sing.artist_name ".
					", (select file_name from "._TBL_IMAGE." I where sing.image_id = I.image_id) image_name ".
					", gen.genre_id , gen.description genre_description ".
					", pr.price_id price_value, pr.description price_description , '1' nb_songs ,time_format(S.length, '%i:%s') length".
					"FROM ".
					_TBL_ARTIST." art,"._TBL_SINGLE." sing,"._TBL_GENRE." gen,"._TBL_PRICE." pr,"._TBL_SONG." S ".
					" where S.genre_id=gen.genre_id AND sing.song_id = S.song_id and pr.price_id = sing.price_id AND art.pseudo = S.pseudo ".
					" AND ( sing.artist_name like '%$string%' OR S.title like '%$string%' OR gen.description like '%$string%' ) ".
	      			" AND art.active='Y' AND art.approval_status='A'  AND song.active='Y' AND sing.active='Y' AND gen.active ='Y' ";
			break;
			case "BLOG":
				$query="SELECT B.title ,.pseudo,S.title, sing.artist_name ".
					", (select file_name from "._TBL_IMAGE." I where sing.image_id = I.image_id) image_name ".
					", gen.genre_id , gen.description genre_description ".
					", pr.price_id price_value, pr.description price_description , '1' nb_songs ,time_format(S.length, '%i:%s') length".
					"FROM ".
					_TBL_ARTIST." art,"._TBL_SINGLE." sing,"._TBL_GENRE." gen,"._TBL_PRICE." pr,"._TBL_SONG." S ".
					" where S.genre_id=gen.genre_id AND sing.song_id = S.song_id and pr.price_id = sing.price_id AND art.pseudo = S.pseudo ".
					" AND ( sing.artist_name like '%$string%' OR S.title like '%$string%' OR gen.description like '%$string%' ) ".
	      			" AND art.active='Y' AND art.approval_status='A' AND sing.active='Y' AND gen.active ='Y' ";
			break;
			}
		if(trim($type) && trim($string)){
/*
      $query_album= "SELECT 'album' item_type, album_id item_id, art.pseudo,album_name description,alb.image_name ".
              ", alb.genre_id , gen.description genre_description,price_id price_value, price_description ,alb.nb_songs, alb.album_length length".
              " from album_view alb , "._TBL_ARTIST." art , "._TBL_GENRE." gen ".
              " WHERE alb.pseudo = art.pseudo AND alb.genre_id = gen.genre_id";
	    $query_album.=" AND ( alb.pseudo like '%$string%' OR alb.album_name like '%$string%' ".
	    							"OR alb.description like '%$string%' OR gen.description like '%$string%' ) ";
      $query_album.=" AND art.active='Y' AND art.approval_status='A' AND gen.active ='Y' AND alb.active ='Y'";

			if ($type=='SINGLE') $query=$query_single; elseif ($type=='ALBUM') $query=$query_album;
			elseif ($type=='ALL') $query=$query_single ." UNION ".$query_album." ORDER BY item_type, pseudo";
*/
//			echo "quey =$query / type =$type ";
		return ($this->fetchData($query));
		}
	}

	function getNewItems($type, $string){
    $query_single= "SELECT 'single' item_type,sing.single_id item_id,art.pseudo,sing.description".
           ", (select file_name from "._TBL_FILE." I where sing.image_id = I.file_id) image_name ".
           ", gen.genre_id , gen.description genre_description ".
           ", pr.price_id price_value, pr.description price_description , '1' nb_songs ".
           " ,time_format(f.length, '%i:%s') length".
              "FROM "._TBL_ARTIST." art , "._TBL_SINGLE." sing, "._TBL_GENRE." gen, "._TBL_PRICE." pr, "._TBL_FILE." f ".
              " where ".
              " sing.genre_id=gen.genre_id  ".
              " AND sing.file_id = f.file_id ".
              " and pr.price_id = sing.price_id ".
              " AND art.pseudo = sing.pseudo ";
    $query_single.=" AND ( sing.creation_date >= DATE_SUB(CURDATE(),INTERVAL "._NEW_ITEM_DAYS_RANGE." DAY)) ".
                    " AND art.active='Y' AND art.approval_status='A' AND sing.active='Y' AND gen.active ='Y' ";

    $query_album= "SELECT 'album' item_type, album_id item_id, art.pseudo,album_name description,alb.image_name ".
            ", alb.genre_id , gen.description genre_description,price_id price_value, price_description ,alb.nb_songs, alb.album_length length".
            " from album_view alb ".
            ", "._TBL_ARTIST." art ".
            ", "._TBL_GENRE." gen ".
            " WHERE ".
            " alb.pseudo = art.pseudo ".
            " AND alb.genre_id = gen.genre_id";
    $query_album.=" AND ( alb.creation_date >= DATE_SUB(CURDATE(),INTERVAL "._NEW_ITEM_DAYS_RANGE." DAY)) ";
    $query_album.=" AND art.active='Y' AND art.approval_status='A' AND gen.active ='Y' AND alb.active ='Y'";

    $query=$query_single ." UNION ".$query_album." ORDER BY item_type, pseudo";	return ($this->fetchData($query));
	}

	/*
	 * Perform research based on the request type
	 */
	function searchMusic($type, $str){

		switch($type){
			case 'album':
				$q= "SELECT  'alb' type,album_id id, art.artist_name artist,album_name name,alb.image_id, i.file_type img_type,alb.image_id img_id, r.description rate
					, alb.genre_id , gen.description genre,alb.price_id price_val, p.description price_desc,alb.nb_songs, alb.album_length length
					 from "._TBL_ALBUM." alb , "._TBL_ARTIST." art , "._TBL_GENRE." gen , "._TBL_ENCODING." r, "._TBL_IMAGE." i , "._TBL_PRICE." p
					 WHERE alb.artist_name = art.artist_name AND alb.genre_id=gen.genre_id AND alb.rate_id=r.rate_id and alb.image_id = i.image_id AND alb.price_id=p.price_id AND ( ";
				unset($sq);
				foreach($str as $v) {
					if(isset($sq)){ $sq.=" AND (alb.artist_name like '%$v%' OR alb.album_name like '%$v%' OR alb.description like '%$v%' OR gen.description like '%$v%' )"; }
					else{  $sq.=" (alb.artist_name like '%$v%' OR alb.album_name like '%$v%' OR alb.description like '%$v%' OR gen.description like '%$v%') "; }
				}
				 $q.=$sq.") AND art.active='Y' AND art.approval_status='A' AND gen.active ='Y' AND alb.active ='Y'";
			break;

			case "song":
				$q="SELECT 'sing' type,sing.single_id id,art.pseudo,S.title name, sing.artist_name artist ".
					", sing.image_id, img.file_type img_type ".
					", gen.genre_id , gen.description genre, enc.description rate ".
					", pr.price_id price_val, pr.description price_desc , '1' nb_songs ,time_format(S.length, '%i:%s') length ".
					" FROM ".
					_TBL_ARTIST." art,"._TBL_SINGLE." sing,"._TBL_GENRE." gen,"._TBL_PRICE." pr,"._TBL_SONG." S, "._TBL_ENCODING." enc ,"._TBL_IMAGE." img".
					" where art.artist_name=sing.artist_name AND sing.image_id = img.image_id and sing.song_id=S.song_id ".
					" and S.genre_id=gen.genre_id AND  pr.price_id=sing.price_id AND  S.rate_id=enc.rate_id AND ( ";
				unset($sq);
				foreach($str as $v) {
					if(isset($sq)){ $sq.=" AND (sing.artist_name like '%$v%' OR S.title like '%$v%' OR gen.description like '%$v%' )";}
					else{ $sq.=" (sing.artist_name like '%$v%' OR S.title like '%$v%' OR gen.description like '%$v%' ) "; }
				}
				$q.=$sq." ) AND art.active='Y' AND art.approval_status='A' AND  S.active='Y' AND sing.active='Y' AND gen.active ='Y' ";
			break;
		}
		if(trim($type) && trim($str)){ return ($this->fetchData($q)); }
	}

	/*
	 * Retrive all items not posted to Social networking
	 */
	function itemsNoTweeted(){

		$q="(SELECT  'alb' type,
			CONCAT(artist_name, ', \"',alb.album_name, '\" est disponible sur "._DOMAIN." : "._SITE."/album/',alb.album_id)
			from "._TBL_ALBUM." alb , "._TBL_ARTIST." art , "._TBL_GENRE." gen , "._TBL_ENCODING." r, "._TBL_IMAGE." i , "._TBL_PRICE." p
			WHERE alb.artist_name = art.artist_name AND alb.genre_id=gen.genre_id AND alb.rate_id=r.rate_id and alb.image_id = i.image_id AND alb.price_id=p.price_id
			AND art.active='Y' AND art.approval_status='A'
			AND gen.active ='Y' AND alb.active ='Y'
			and (alb.release_date <= now() or alb.release_date is NULL)
			AND alb.socialPosted  IS NULL
			ORDER BY alb.release_date desc, alb.creation_date DESC
			 LIMIT 0,10
			)

			UNION

			(SELECT 'sing' type,
			CONCAT('Ecoute les titres de ',art.artist_name,'  sur "._DOMAIN." : "._SITE."/artist/',REPLACE (artist_name, ' ', '+'))
            FROM "._TBL_ARTIST." art , "._TBL_SINGLE." sing , "._TBL_GENRE." gen , "._TBL_PRICE." pr
            , "._TBL_IMAGE." img , "._TBL_SONG." song , "._TBL_ENCODING." rate
             where
            sing.song_id = song.song_id AND sing.image_id=img.image_id  AND song.genre_id=gen.genre_id
            and song.rate_id = rate.rate_id and pr.price_id = sing.price_id  and pr.price_id = sing.price_id
            AND art.artist_name = sing.artist_name
            AND song.active='Y' AND art.active='Y' AND art.approval_status='A'
    		AND sing.active='Y' AND gen.active ='Y'
    		AND (song.release_date <=now() OR song.release_date IS NULL )
    		ORDER BY sing.creation_date DESC
    		group by art.artist_name LIMIT 0,10
    		)
    		";
				//   echo "<br>sql =>$q";
		 return ($this->fetchData($q));
	}

	function searchAlbumSongs($str){
		$q_s= "SELECT 'sing' type,sing.single_id id,art.artist_name artist,S.title name".
				", sing.image_id, img.file_type img_type , r.description rate ".
				", gen.genre_id , gen.description genre ".
				", pr.price_id price_val, pr.description price_desc , '1' nb_songs ".
				" ,time_format(S.length, '%i:%s') length ".
				"FROM "._TBL_ARTIST." art , "._TBL_SINGLE." sing, "._TBL_GENRE." gen, "._TBL_PRICE." pr, "._TBL_SONG." S , "._TBL_ENCODING." r , "._TBL_IMAGE." img ".
				" where ".
	            " art.artist_name=sing.artist_name AND S.rate_id = r.rate_id and S.genre_id=gen.genre_id AND sing.song_id = S.song_id and sing.image_id=img.image_id and pr.price_id = sing.price_id AND (";
				unset($sq);
				foreach($str as $v) {
					if(isset($sq)){ $sq.=" AND (sing.artist_name like '%$v%' OR S.title like '%$v%' OR gen.description like '%$v%' )"; }
					else{  $sq.=" (sing.artist_name like '%$v%' OR S.title like '%$v%' OR gen.description like '%$v%') "; }
				}
				 $q_s.=$sq.") AND art.active='Y' AND art.approval_status='A'  AND S.active='Y' AND sing.active='Y' AND gen.active ='Y' ".
				 " AND (S.release_date <=now() OR S.release_date IS NULL ) ";

		$q_a = "SELECT 'alb' type, album_id id, art.artist_name artist, alb.album_name name,alb.image_id, i.file_type img_type,r.description rate ".
	            ", alb.genre_id , gen.description genre,alb.price_id price_val, p.description price_desc,alb.nb_songs, alb.album_length length".
	            " from "._TBL_ALBUM." alb ".
	            ", "._TBL_ARTIST." art , "._TBL_GENRE." gen , "._TBL_ENCODING." r , "._TBL_IMAGE." i , "._TBL_PRICE." p ".
	            " WHERE
	            alb.artist_name = art.artist_name AND alb.genre_id = gen.genre_id AND alb.rate_id=r.rate_id
                AND alb.price_id = p.price_id AND alb.image_id = i.image_id AND ( ";

				unset($sq);
				foreach($str as $v) {
					if(isset($sq)){ $sq.=" AND (alb.artist_name like '%$v%' OR alb.album_name like '%$v%' OR alb.description like '%$v%' OR gen.description like '%$v%' )"; }
					else{  $sq.=" (alb.artist_name like '%$v%' OR alb.album_name like '%$v%' OR alb.description like '%$v%' OR gen.description like '%$v%') "; }
				}

				 $q_a.=$sq.") AND art.active='Y' AND art.approval_status='A' AND gen.active ='Y' AND alb.active ='Y'".
				 " AND (alb.release_date <= NOW() OR alb.release_date IS NULL  ) ";


	    $q=$q_s ." UNION ".$q_a." ORDER BY type, artist";
        // echo "query / $query";
        return ($this->fetchData($q));
	}

	function getArtistSales($pseudo, $p=1){
	//  if ($period==0) $date=" date_format(transaction_date,GET_FORMAT(DATE,'USA'))= date_format(CURDATE(),GET_FORMAT(DATE,'USA'))"; // Today
//		else
		if ($p==1) $date=" date_format(transaction_date,GET_FORMAT(DATE,'USA'))= date_format(CURDATE()-1,GET_FORMAT(DATE,'USA'))"; //Yesterday
//		elseif ($period==2) $date=" YEARWEEK(transaction_date) = YEARWEEK(CURRENT_DATE()) "; //this week
//		elseif ($period==3) $date=" YEARWEEK(transaction_date) = YEARWEEK(CURRENT_DATE)-1"; // last week
		//elseif ($period==3) $date=" YEARWEEK(date_cde) = YEARWEEK(CURRENT_DATE - INTERVAL 7 DAY)"; // last week
//		elseif ($period==4) $date=" date_format(transaction_date,'%Y-%m') = date_format(CURDATE(),'%Y-%m')"; //this month
//		elseif ($period==5) $date=" date_format(transaction_date,'%Y-%m') = date_format(DATE_SUB(CURDATE(),INTERVAL 1 MONTH),'%Y-%m')"; //last month
		//		elseif ($period==6) $date=" transaction_date >= DATE_SUB(CURDATE(),INTERVAL 2 MONTH)"; // last 3 months-> previously

		elseif ($p==2) $date=" transaction_date between DATE_SUB(CURDATE(),INTERVAL 7 day) and CURDATE() "; //this week
		elseif ($p==3) $date=" transaction_date between DATE_SUB(CURDATE(),INTERVAL 14 day) and CURDATE()"; // last week
		elseif ($p==4) $date="transaction_date between DATE_SUB(CURDATE(),INTERVAL 1 MONTH) and CURDATE()"; //this month
		elseif ($p==5) $date=" transaction_date between DATE_SUB(CURDATE(),INTERVAL 2 MONTH) and CURDATE()"; //last month
		elseif ($p==6) $date=" transaction_date between DATE_SUB(CURDATE(),INTERVAL 3 MONTH) and CURDATE() "; // last 3 months
		elseif ($p==7) $date=" transaction_date between DATE_SUB(CURDATE(),INTERVAL 6 MONTH) and CURDATE() "; // last 6 months
		elseif ($p==8) $date=" transaction_date between DATE_SUB(CURDATE(),INTERVAL 12 MONTH) and CURDATE() "; // last 3 months

	  $query = "select DATE_FORMAT(transaction_date,GET_FORMAT(DATE,'EUR')) date, item_type, item_description, payment_description payment,price_ttc, price_ht,price_net_ht,revenue_percentage,amount_due ".
	          " FROM "._TBL_ARTIST_BALANCE." where pseudo='$pseudo'  AND $date ORDER BY transaction_date DESC "; return ($this->fetchData($query));
	}
	/* List all listening entries for the given artist */
	function getStatAlbumArtist($pseudo,$p=0 ){
		if(trim($pseudo)){
			if ($p==0) $date=" date_format(s.date,'%Y-%m-%d')= date_format(CURDATE(),'%Y-%m-%d')"; // Today
			elseif ($p==1) $date=" s.date between DATE_SUB(CURDATE(),INTERVAL 2 day) and CURDATE() "; //this week
			elseif ($p==2) $date=" s.date between DATE_SUB(CURDATE(),INTERVAL 7 day) and CURDATE() "; //this week
			elseif ($p==3) $date=" s.date between DATE_SUB(CURDATE(),INTERVAL 14 day) and CURDATE()"; // last week
			elseif ($p==4) $date=" s.date between DATE_SUB(CURDATE(),INTERVAL 1 MONTH) and CURDATE()"; //this month
			elseif ($p==5) $date=" s.date between DATE_SUB(CURDATE(),INTERVAL 2 MONTH) and CURDATE()"; //last month
			elseif ($p==6) $date=" s.date between DATE_SUB(CURDATE(),INTERVAL 3 MONTH) and CURDATE() "; // last 3 months
			elseif ($p==7) $date=" s.date between DATE_SUB(CURDATE(),INTERVAL 6 MONTH) and CURDATE() "; // last 6 months
			elseif ($p==8) $date=" s.date between DATE_SUB(CURDATE(),INTERVAL 12 MONTH) and CURDATE() "; // last 3 months
			$query = "select count(s.stats_id)nb, album_name name,date_format(s.date,'%d-%m-%Y') date ".
							" from "._TBL_STAT_ALBUM." s, "._TBL_ALBUM." alb , "._TBL_ARTIST." art".
							" where s.album_id = alb.album_id and alb.artist_name=art.artist_name and art.pseudo='$pseudo' AND $date ".
							" group by s.date,s.album_id ORDER BY s.date DESC, album_name ASC ";
			return ($this->fetchData($query));
		}
	}

	/* List all listening entries for the given artist */
	function getStatSongArtist($pseudo,$p=0 ){
		if(trim($pseudo)){
			if ($p==0) $date=" date_format(stat.date,'%Y-%m-%d')= date_format(CURDATE(),'%Y-%m-%d')"; // Today
			elseif ($p==1) $date=" stat.date between DATE_SUB(CURDATE(),INTERVAL 2 day) and CURDATE() "; //this week
			elseif ($p==2) $date=" stat.date between DATE_SUB(CURDATE(),INTERVAL 7 day) and CURDATE() "; //this week
			elseif ($p==3) $date=" stat.date between DATE_SUB(CURDATE(),INTERVAL 14 day) and CURDATE()"; // last week
			elseif ($p==4) $date=" stat.date between DATE_SUB(CURDATE(),INTERVAL 1 MONTH) and CURDATE()"; //this month
			elseif ($p==5) $date=" stat.date between DATE_SUB(CURDATE(),INTERVAL 2 MONTH) and CURDATE()"; //last month
			elseif ($p==6) $date=" stat.date between DATE_SUB(CURDATE(),INTERVAL 3 MONTH) and CURDATE() "; // last 3 months
			elseif ($p==7) $date=" stat.date between DATE_SUB(CURDATE(),INTERVAL 6 MONTH) and CURDATE() "; // last 6 months
			elseif ($p==8) $date=" stat.date between DATE_SUB(CURDATE(),INTERVAL 12 MONTH) and CURDATE() "; // last 3 months
			$query =" select  count(stat.song_id) nb, stat.title name ,stat.song_id,date_format(stat.date,'%d-%m-%Y') date ".
              " from  ( ".
              " select sng1.song_id, s.date, sng1.title ".
              " from "._TBL_STAT_SONG." s, "._TBL_SONG." sng1 ".
              " where s.song_id = sng1.song_id and sng1.pseudo='$pseudo' ".
              " UNION ALL ".
              " select sng2.song_id, s1.date ,sng2.title ".
              " from "._TBL_STAT_SINGLE." s1,"._TBL_SINGLE." sing,"._TBL_SONG." sng2 ".
              " where s1.single_id = sing.single_id and sing.song_id=sng2.song_id and sng2.pseudo='$pseudo' ".
              " )  stat ".
							" where  $date GROUP by stat.date,stat.song_id ORDER BY stat.date DESC, stat.title ASC ";
			return ($this->fetchData($query));
		}
	}

	function getStatDownloadArtist($pseudo,$p=0 ){
		if(trim($pseudo)){
			if ($p==0) $date=" trx_date= date_format(CURDATE(),'%Y-%m-%d')"; // Today
			elseif ($p==1) $date=" trx_date between DATE_SUB(CURDATE(),INTERVAL 2 day) and CURDATE() "; //this week
			elseif ($p==2) $date=" trx_date between DATE_SUB(CURDATE(),INTERVAL 7 day) and CURDATE() "; //this week
			elseif ($p==3) $date=" trx_date between DATE_SUB(CURDATE(),INTERVAL 14 day) and CURDATE()"; // last week
			elseif ($p==4) $date=" trx_date between DATE_SUB(CURDATE(),INTERVAL 1 MONTH) and CURDATE()"; //this month
			elseif ($p==5) $date=" trx_date between DATE_SUB(CURDATE(),INTERVAL 2 MONTH) and CURDATE()"; //last month
			elseif ($p==6) $date=" trx_date between DATE_SUB(CURDATE(),INTERVAL 3 MONTH) and CURDATE() "; // last 3 months
			elseif ($p==7) $date=" trx_date between DATE_SUB(CURDATE(),INTERVAL 6 MONTH) and CURDATE() "; // last 6 months
			elseif ($p==8) $date=" trx_date between DATE_SUB(CURDATE(),INTERVAL 12 MONTH) and CURDATE() "; // last 3 months
			$query ="select count(item_id) nb, trx_date date, title name ,type ".
              " from ".
              " ( ".
              "   select si.artist_name, item_type type, date_format(ol.downloaded_date,'%Y-%m-%d') trx_date, ".
              "     si.single_id item_id, so.title ".
              "   from order_lines ol, singles si , song_files so ,payment_audit p ".
              "   where  ".
              "   ol.item_id = si.single_id and si.song_id = so.song_id and ol.item_type='single' and ol.order_id= p.order_id ".
              "   and p.payment_status='Valid' and ol.downloaded='Y' and so.pseudo = '$pseudo' ".
              "   UNION ALL ".
              "   select al.artist_name, item_type type, date_format(ol.downloaded_date,'%Y-%m-%d') trx_date ".
              "   , al.album_id item_id, al.album_name ".
              "   from order_lines ol, albums al, artists ar ,payment_audit p ".
              "   where ".
              "   ol.item_id = al.album_id and ol.order_id= p.order_id and al.artist_name = ar.artist_name ".
              "   and ol.item_type='album' and p.payment_status='Valid' and ol.downloaded='Y'  and ar.pseudo = '$pseudo' ".
              " ) dl ".
              " where 1 and $date group by trx_date,item_id ORDER BY dl.trx_date DESC ";
			return ($this->fetchData($query));
		}
	}

	/*
	 * Perform research based on the request type
	 */
	function listSpecialItem(){
		if(trim($type) && trim($string)){
			$query_single= "SELECT 'single' item_type,sing.single_id item_id,art.pseudo,sing.description".
	      		 ", (select file_name from "._TBL_FILE." I where sing.image_id = I.file_id) image_name ".
	      		 ", gen.genre_id , gen.description genre_description ".
	      		 ", pr.price_id price_value, pr.description price_description , '1' nb_songs ".
	      		 " ,time_format(f.length, '%i:%s') length".
	              "FROM ".
	              _TBL_ARTIST." art ".
	              ", "._TBL_SINGLE." sing ".
	              ", "._TBL_GENRE." gen  ".
	              ", "._TBL_PRICE." pr ".
	              ", "._TBL_FILE." f ".
	              " where ".
	              " sing.genre_id=gen.genre_id  ".
	              " AND sing.file_id = f.file_id ".
	              " and pr.price_id = sing.price_id ".
	              " AND art.pseudo = sing.pseudo ";
	    $query_single.=" AND ( sing.pseudo like '%$string%' OR sing.description like '%$string%' ".
	      							"				OR gen.description like '%$string%' ) ".
	      							" AND art.active='Y' AND art.approval_status='A' AND sing.active='Y' AND gen.active ='Y' ";

      $query_album= "SELECT 'album' item_type, album_id item_id, art.pseudo,album_name description,alb.image_name ".
              ", alb.genre_id , gen.description genre_description,price_id price_value, price_description ,alb.nb_songs, alb.album_length length".
              " from album_view alb , "._TBL_ARTIST." art, "._TBL_GENRE." gen ".
              " WHERE alb.pseudo = art.pseudo AND alb.genre_id = gen.genre_id";
	    $query_album.=" AND ( alb.pseudo like '%$string%' OR alb.album_name like '%$string%' ".
	    							"OR alb.description like '%$string%' OR gen.description like '%$string%' ) ";
      $query_album.=" AND art.active='Y' AND art.approval_status='A' AND gen.active ='Y' AND alb.active ='Y'";

			if ($type=='SINGLE') $query=$query_single; 	elseif ($type=='ALBUM') $query=$query_album;
			elseif ($type=='ALL') $query=$query_single ." UNION ".$query_album;
			$query.=" ORDER BY item_type, pseudo"; 	return ($this->fetchData($query));
		}
	}
	function calculatePayment($pseudo=''){
	  $query = "Replace "._TBL_ARTIST_BALANCE." (artist_name,order_id,order_line_id,pseudo,item_description,item_type,item_id, payment_description, transaction_date,price_ttc,price_net_ttc,price_ht,revenue_percentage,amount_due)  ".
		        "select art.artist_name,ol.order_id,order_line_id,art.pseudo, alb.album_name, ol.item_type, ol.item_id, pt.payment_id payment_type, ol.transaction_date ".
						", ol.price priceTTC ".
						", (ol.price - (ol.price* (pt.cost_rate/100)) - pt.fixed_cost) priceNet_TTC ".
						",  ( (ol.price - (ol.price* (pt.cost_rate/100)) - pt.fixed_cost) /(1+(ol.vat_rate/100)))  priceHT ".
						", arr.revenue_rate ".
						",  (( (ol.price - (ol.price* (pt.cost_rate/100)) - pt.fixed_cost) /(1+(ol.vat_rate/100))) * (arr.revenue_rate /100) ) amount_due ".
						" from "._TBL_ORDER." ord,"._TBL_ARTIST." art,"._TBL_PAYMENT_TYPE." pt,"._TBL_ORDER_LINE." ol".
						" ,"._TBL_ALBUM." alb,"._TBL_PAYMENT_AUDIT." paud ,"._TBL_ARTIST_RATES." arr ".
						" where ".
						" ord.order_id = ol.order_id AND alb.album_id = ol.item_id ".
						" AND ord.order_id=paud.order_id and pt.payment_id=paud.payment_type_id ".
						" AND art.artist_name=alb.artist_name AND ol.item_type='album' and paud.payment_status='Valid'".
						" AND DATE_FORMAT(ol.transaction_date,'%Y-%m-%d') >= DATE_SUB(CURDATE(), INTERVAL 3 DAY) ".
						" AND art.artist_name=arr.artist_name ".
						" AND art.pseudo = arr.pseudo ".
						" AND ol.transaction_date >= arr.date_active_from  ".
						" AND ( (arr.date_active_to > ol.transaction_date) OR arr.date_active_to IS NULL ) ".
						" AND ol.price >0 ".
				" UNION ".
		        " select art.artist_name,ol.order_id,order_line_id,art.pseudo, song.title, ol.item_type, ol.item_id, pt.payment_id payment_type, ol.transaction_date ".
						", ol.price priceTTC ".
						", (ol.price - (ol.price* (pt.cost_rate/100)) - pt.fixed_cost) priceNet_TTC ".
						",  ( (ol.price - (ol.price* (pt.cost_rate/100)) - pt.fixed_cost) /(1+(ol.vat_rate/100)))  priceHT ".
						", arr.revenue_rate ".
						",  (( (ol.price - (ol.price* (pt.cost_rate/100)) - pt.fixed_cost) /(1+(ol.vat_rate/100))) * (arr.revenue_rate /100) ) amount_due ".
						" from "._TBL_ORDER." ord,"._TBL_ARTIST." art,"._TBL_PAYMENT_TYPE." pt,"._TBL_ORDER_LINE." ol ".
						" ,"._TBL_PAYMENT_AUDIT." paud,"._TBL_SINGLE." sing,"._TBL_SONG." song ,"._TBL_ARTIST_RATES." arr ".
						" where ".
						"ord.order_id = ol.order_id AND sing.single_id = ol.item_id AND sing.song_id = song.song_id ".
						" AND ord.order_id=paud.order_id and pt.payment_id=paud.payment_type_id ".
						" AND sing.artist_name=art.artist_name ".
						" AND ol.item_type='single' and paud.payment_status='Valid'".
						" AND DATE_FORMAT(ol.transaction_date,'%Y-%m-%d') >= DATE_SUB(CURDATE(), INTERVAL 3 DAY) ".
						" AND art.artist_name=arr.artist_name ".
						" AND art.pseudo = arr.pseudo ".
						" AND ol.transaction_date >= arr.date_active_from  ".
						" and ( (arr.date_active_to > ol.transaction_date) OR arr.date_active_to IS NULL ) ".
						" AND ol.price >0  AND song.active='Y' ";
		return ($this->fetchData($query));
	}

	function emailValidation($regNum,$email){
		$this->db->query("DELETE FROM "._TBL_EMAIL." WHERE email='$email'");
		$query="REPLACE "._TBL_EMAIL." (email,reg_num,creation_date) values ('$email','$regNum',now())";
		$result=$this->db->query($query); if($result) return (true); else return (false);
	}

	function regnumExist($reg_num){
	  $result=$this->db->query("SELECT count(email) FROM "._TBL_EMAIL." where reg_num='$reg_num'");
    if ($this->db->error()) return(false);
    else{list($item) = mysql_fetch_row($result);if(isset($item) && $item >0) return (true);else return (false);}
	}

	function retrieveRegnumUserInfo($reg_num){
		$errorFlag=false;
		$query="SELECT E.email,E.reg_num, P.language_pref lang,S.user_type FROM "._TBL_EMAIL." E,"._TBL_SUBSCRIBER."S, "._TBL_SUBSCRIBER_PREF." P ".
								 " where E.email=S.email AND S.pseudo=P.pseudo AND reg_num='$reg_num' LIMIT 0,1";
		return ($this->fetchData($query));
	}

	function confirmEmail($regnum,$email){
		$errorFlag=false;
	    if(trim($email) && trim($regnum)){
			$query="UPDATE "._TBL_SUBSCRIBER." SET email_confirmed='Y' WHERE email='$email' "; $result=$this->db->query($query);
			if(!$result || $this->db->error()) $errorFlag=true;
			else $this->db->query("DELETE FROM "._TBL_EMAIL." WHERE email='$email' and reg_num='$regnum'");
		}
		if($errorFlag==false) return (true);else return (false);
	}

	function replaceEmail($oldMail,$newMail){
		if(isset($newMail) && isset($oldMail) && trim($newMail) && trim($oldMail)){
			$query="UPDATE "._TBL_SUBSCRIBER." SET previous_email='$oldMail', email='$newMail',email_confirmed='N' WHERE email='$oldMail' ";
			$result=$this->db->query($query); if(!$result || $this->db->error()) return (false);else return (true);
		}
	}

	function createNewPlayList($post, $pseudo){
		$query=" insert "._TBL_PLAYLIST."(playlist_id, pseudo,title, creation_date) VALUES ('".uniqid()."','".$pseudo."','".addslashes($post['title'])."',now())";
		$result=$this->db->query($query);		if ($result) return(true); else return(false);
	}
  function playListExist($var,$pseudo){
	  $result = $this->db->query("SELECT count(pseudo) FROM "._TBL_PLAYLIST." where pseudo='$pseudo' and title='".addslashes($var)."'");
    if ($this->db->error()) return(false);
    else{ list($item) = mysql_fetch_row($result); if(isset($item) && $item >0) return (true);else return (false);}
	}

	function getPlayList($pseudo='',$artist=''){
	    if(trim($pseudo)){
	      $query="select playlist_id id, title description from "._TBL_PLAYLIST." where 1 AND pseudo='$pseudo' ORDER by description";
		}
	    if(trim($artist)){
	      $query="select playlist_id id, p.title description from "._TBL_PLAYLIST." p, ".
		  _TBL_ARTIST." a where p.pseudo=a.pseudo AND a.artist_name='$artist' ORDER by description";
		}
		return ($this->fetchData($query));

	}

	function addPlayList($item_type,$item_id,$playlist_id){
		$query=" REPLACE "._TBL_PLAYLIST_ITEM."(item_type,item_id,playlist_id) VALUES ('".$item_type."','".$item_id."','".$playlist_id."')";
		$result=$this->db->query($query);		if ($result) return(true); else return(false);
	}

  function addCommentPage($pseudo,$comment,$pageID){
		$query=" REPLACE "._TBL_COMMENT_PAGE."(comment_id,text, pseudo,vibe_id,creation_date,update_date) VALUES ('".uniqid()."','".addslashes($comment)."','".addslashes($pseudo)."','".addslashes($pageID)."',now(),now())";
   	$result=$this->db->query($query);		if ($result) return(true); else return(false);
	}

  function addBlogComment($pseudo,$post){
    if(trim($post['comment']) && trim($post['blog_id']) && trim($pseudo)){
  		$query=" insert "._TBL_BLOG_COMMENT."(comment_id,text, pseudo,blog_id,creation_date,update_date) VALUES ('".uniqid()."','".addslashes($post['comment'])."','".addslashes($pseudo)."','".addslashes($post['blog_id'])."',now(),now())";
  		$result=$this->db->query($query);
    }
    if ($result) return(true); else return(false);
	}

	function addLinkRequest($from,$to){
		$query=" insert "._TBL_FRIENDS."(request_id,approved,requestor, pseudo,request_date) VALUES ('".uniqid()."','N','".addslashes($from)."','".addslashes($to)."',now())";
		$result=$this->db->query($query);		if ($result) return(true); else return(false);
	}
	function addMessage($from,$to,$comment,$topic=''){
		$query=" insert "._TBL_MESSAGE."(message_id,pseudo,pseudo_from,text,topic, creation_date) VALUES ('".uniqid()."','".addslashes($to)."','".addslashes($from)."','".addslashes($comment)."','".strip_tags(addslashes($topic))."',now())";
		$result=$this->db->query($query);		if ($result) return(true); else return(false);
	}

    function getMessage($receiver){
        if(isset($receiver)){ $query=" select *, date_format(creation_date, '%d/%m/%Y') date, date_format(creation_date, '%d/%m/%Y - %H:%i') date_time from "._TBL_MESSAGE." where pseudo='$receiver'"; return ($this->fetchData($query));}
    }
    function getLinkRequest($receiver){
        if(isset($receiver)){ $query=" select *,date_format(request_date, '%d/%m/%Y') date from "._TBL_FRIENDS." where pseudo='$receiver' AND approved!='Y'"; return ($this->fetchData($query));}
    }

    function getEmailInfo($pseudo){
        if(isset($pseudo)){ $query=" select S.email,P.language_pref lang from "._TBL_SUBSCRIBER."S, "._TBL_SUBSCRIBER_PREF." P where S.pseudo=P.pseudo and P.pseudo='$pseudo'"; return ($this->fetchData($query));}
    }

	function linkApprove($pseudo,$requestor){
		if(isset($pseudo) && isset($requestor)){ $query=" update "._TBL_FRIENDS." set approved='Y' where pseudo='$pseudo' AND requestor='$requestor'"; $result=$this->db->query($query);	if ($result) return(true); else return(false);}
	}
	function linkReject($pseudo,$requestor){
		if(isset($pseudo) && isset($requestor)){
			$query=" delete from "._TBL_FRIENDS." where pseudo='$pseudo' AND requestor='$requestor'";
			$result=$this->db->query($query); if(!$result || $this->db->error()) return(false) ; else return(true);
		}
	}
	function deleteMessage($pseudo,$varID){
		if(isset($pseudo) && isset($varID)){
			$query=" delete from "._TBL_MESSAGE." where pseudo='$pseudo' AND message_id='$varID'";
			$result=$this->db->query($query); if(!$result || $this->db->error()) return(false) ; else return(true);
		}
	}

  function addBlog($pseudo,$post){
    $errorFlag=false;
        $query="insert "._TBL_BLOG.
              " (blog_id, pseudo, title,content,creation_date,update_date) ".
              " values ('".uniqid()."','".addslashes($pseudo)."','".addslashes($post['title'])."' ,'".addslashes($post['content'])."' ".
              ",now(),now())";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) $errorFlag=true;
    if ($errorFlag) return(false) ; else return(true);
  }

  function updateBlog($pseudo,$post){
        $query="update "._TBL_BLOG.
              " set title='".addslashes($post['title'])."',content='".addslashes($post['content'])."',update_date=now()  ".
              "where blog_id ='".$post['blog_id']."' AND pseudo='".addslashes($pseudo)."'";
        $result=$this->db->query($query);
        if(!$result || $this->db->error()) return(false) ; else return(true);
  }

  function retrieveLastRegisteredArtist(){
    $query="select A.artist_name, S.pseudo,A.exclusivity_flag, DATE_FORMAT(S.creation_date, '%d-%m-%Y' ) date , C.description country, S.email_confirmed from "._TBL_ARTIST." A, "._TBL_SUBSCRIBER." S , "._TBL_COUNTRY." C ".
			"where A.pseudo = S.pseudo AND A.country_id=C.country_id ".
			" AND A.creation_date >= DATE_SUB(CURDATE(),INTERVAL "._PERIOD_REMINDER_EMAIL." DAY) ".
			" ORDER BY A.creation_date DESC LIMIT 0,100";
    return ($this->fetchData($query));

  }


}

?>
