照片归档
===
<br>
## test in php 7.0.0 + yaf 3.0.0
<br>
#### 移动照片，照片生成handle路径和thumb路径
/usr/local/php-7.0.0/bin/php public/cli.php  request_uri="/face/mvImages"
<br>
<br>

#### 分析照片的人脸
/usr/local/php-7.0.0/bin/php public/cli.php  request_uri="/face/analyzerFace"
<br>
<br>

#### 把人脸添加到faceset
/usr/local/php-7.0.0/bin/php public/cli.php  request_uri="/face/analyzerFaceset"
<br>
<br>

#### 训练当前faceset
/usr/local/php-7.0.0/bin/php public/cli.php  request_uri="/face/trainFaceset"
<br>
<br>

#### 删除空文件夹
find ./origin_images -type d -empty -exec rm -rf {} \;
<br>
<br>

##### 通过faceId查找照片
<pre>
<code>
explain  SELECT `images`.`image_id` FROM `images` WHERE  

    exists ( select * from ( select * from faces where face_face_id='f51f426269487072d8db0777dd1d2663' 
                UNION all select * from faces where face_face_id='fe3ef528caf927dceb49097a8797fc' ) as a where a.image_id=images.image_id
            )
    AND 
    `images`.`image_id` NOT IN (10,9)
    
    GROUP BY `images`.`image_id`
</code>
</pre>
