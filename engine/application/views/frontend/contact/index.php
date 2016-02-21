<!-- main article detail -->
<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Hubungi Kami</h2>
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <?php if ($showform):?>
        <div class="row">
            <form method="post" action="<?php echo site_url('contact/save'); ?>">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?php echo $contactus?$contactus['name']:''; ?>" placeholder="Masukkan nama lengkap" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="text" class="form-control" name="email" id="email" value="<?php echo $contactus?$contactus['email']:''; ?>" placeholder="Masukkan alamat email" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="subject">Subjek</label>
                        <input type="text" class="form-control" name="subject" id="subject" value="<?php echo $contactus?$contactus['subject']:''; ?>" placeholder="Masukkan subjek pesan" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="content">Isi Pesan</label>
                        <textarea maxlength="700" class="form-control" name="content" id="content" rows="10" value="<?php echo $contactus?$contactus['content']:''; ?>" placeholder="Masukkan pesan anda"></textarea>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="captcha"><span style="font-size: 15px;">Masukkan kode ini <code><?php echo $captcha['word']; ?></code></span></label>
                        <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Masukkan kode captcha di atas" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit" />
                        <input type="reset" class="btn btn-default" value="Reset" />
                    </div>
                </div>
            </form>
        </div>
        <?php else: ?>
        <p style="font-size: 15px;">Kirim pesan lainnya ke redaksi dengan mengklik <a class="btn btn-link" href="<?php echo site_url('contact'); ?>">link</a> ini.</p>
        <?php endif; ?>
    </div>
</div>