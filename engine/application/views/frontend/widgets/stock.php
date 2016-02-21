<div class="widget">
    <div class="inner">
        <?php if ($rates): ?>
        <div class="tabbable"> <!-- Only required for left/right tabs -->
            <ul class="nav nav-tabs">
                <?php foreach ($rates as $index=>$rate): ?>
                <li <?php echo $index==0?'class="first-child active"':''; ?>>
                    <a data-toggle="tab" href="#tab_<?php echo $rate->bank; ?>">
                        <div class="inner-tab"><?php echo strtoupper($rate->bank); ?></div>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="tab-content">
                <?php foreach ($rates as $index=>$rate): ?>
                <div id="tab_<?php echo $rate->bank; ?>" class="tab-pane <?php echo $index==0?' active':''; ?>">
                    <div class="nicescroll" style="height:280px;overflow:hidden;">
                        <table role="table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kurs</th>
                                    <th class="text-right">Jual</th>
                                    <th class="text-right">Beli</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rate->rates as $cur): ?>
                                <tr>
                                    <td><?php echo strtoupper($cur->name); ?></td>
                                    <td class="text-right"><?php echo number_format($cur->sell,2,',','.'); ?></td>
                                    <td class="text-right"><?php echo number_format($cur->buy,2,',','.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>