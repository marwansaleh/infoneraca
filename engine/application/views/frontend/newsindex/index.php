<div class="row">
    <div class="blog-style" style="margin-top:10px; margin-bottom:10px;">
        <form method="post" action="<?php echo site_url('newsindex'); ?>">
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-2">
                        <select class="form-control" name="index_day">
                            <option value="0" <?php echo isset($index_day)&&$index_day==0?'selected':''; ?>>Tanggal</option>
                            <?php for($day=1;$day<=31;$day++): ?>
                            <option value="<?php echo $day; ?>" <?php echo isset($index_day)&&$index_day==$day?'selected':''; ?>><?php echo str_pad($day, 2, "0", STR_PAD_LEFT); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <select class="form-control" name="index_month">
                            <option value="0" <?php echo isset($index_month)&&$index_month==0?'selected':''; ?>>Pilih Bulan</option>
                            <?php foreach ($indonesian_months as $m_index => $m_name): ?>
                            <option value="<?php echo $m_index; ?>" <?php echo isset($index_month)&&$index_month==$m_index?'selected':''; ?>><?php echo $m_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" name="index_year">
                            <option value="0" <?php echo isset($index_year)&&$index_year==0?'selected':''; ?>>Tahun</option>
                            <?php for($year=$article_years['min'];$year<=$article_years['max'];$year++): ?>
                            <option value="<?php echo $year; ?>" <?php echo isset($index_year)&&$index_year==$year?'selected':''; ?>><?php echo $year; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-default btn-primary btn-block" type="submit">Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
    
<div class="row">
    <ul class="newslist">
        <?php foreach ($articles as $article): ?>
        <li>
            <p class="category-name"><?php echo strtoupper($article->category_name); ?></p>
            <h4>
                <a href="<?php echo site_url('detail/'.$article->url_title); ?>"><?php echo $article->title; ?></a>
            </h4>
            <span class="text-muted small"><em><?php echo date('D, d M Y H:i',$article->date); ?></em></span>
            <p>
                <?php echo $article->synopsis; ?> 
                ...<a href="<?php echo site_url('detail/'.$article->url_title); ?>"><span style="font-weight: bold; color: blue;">more</span></a>
            </p>
        </li>
        <?php endforeach; ?>
    </ul>
</div>