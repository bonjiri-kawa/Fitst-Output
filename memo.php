<?php
                if(!empty($bordData)){
                  foreach($bordData as $key => $val){
                    if(!empty($val['msg'])){
                      $msg = array_shift($val['msg']);
                      $partner_info = array_shift($val['partner_info']);
              ?>
                <tr>
                  <td><?php echo sanitize(date('Y.m.d H:i:s', strtotime($msg['send_date']))); ?></td>
                  <td><?php echo sanitize($partner_info['username']); ?></td>
                  <td><a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>"><?php echo mb_substr(sanitize($msg['msg']),0,40); ?></a>...</td>
                </tr>
              <?php
                    }else{
              ?>
                <tr>
                  <td>--</td>
                  <td>◯◯ ◯◯</td>
                  <td><a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>">まだメッセージはありません</a></td>
                </tr>
                <?php
                    }
                  }
                }
              ?>