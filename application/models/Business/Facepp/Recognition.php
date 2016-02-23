<?php
/**
 * @file Recognition.php
 * @brief 
 * @author jackie <jackie@digiocean.cc>
 * @version 1.0
 * @date 2016-01-12
 */
namespace Business\Facepp;

class RecognitionModel extends \Business\AbstractModel{

    public function search( $faceId ){
        $facesets = \Dao\FacesetModel::getInstance()->getUsableSearchFaceset();
        if( count( $facesets ) > 0 ){
            $face = \Yaf_Registry::get('facepp');
            $conf = \Yaf_Registry::get('face');
            $result = array();
            foreach( $facesets as $item ){
                $params = array();
                $params['key_face_id'] = $faceId;
                $params['faceset_id'] = $item['face_faceset_id'];
                $params['count'] = $conf['search_count'];
                $response = $face->execute( '/recognition/search', $params );
                if( $response['http_code'] == 200 ){
                    $body = json_decode( $response['body'], true );
                    if( is_array( $body['candidate'] ) ){
                        foreach( $body['candidate'] as $c ){
                            if( $c['similarity'] >= $conf['same_candidate'] ){
                                //$result['same'][] = $c['face_id'];
                                $result['same'][] = $c;
                                continue;
                            }

                            if( $c['similarity'] >= $conf['maybe_candidate'] ){
                                //$result['maybe'][] = $c['face_id'];
                                $result['maybe'][] = $c;
                            }


                        }
                    }
                }
            }

            return $result;

        }
        return false;
    }
}
?>
