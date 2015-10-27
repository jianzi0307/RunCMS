#!/bin/sh
# =======================
# sphinx 实时更新解决方案有两种：
# 方案1、使用分布式索引
# 方案2、主索引+增量索引合并

# ======================> 方案1 <=======================================
# 更新增量索引 */1 * * * * /bin/sh /data/www/rendawushui/Data/cron/sphinx_index_update.sh
/usr/local/sphinx/bin/indexer --config /usr/local/sphinx/etc/sphinx.conf --rotate delta_expsys >> /usr/local/sphinx/var/log/delta_expsys.log 
# 使用分布式索引作为数据源检索数据




# ======================> 方案2 <=======================================
# 更新增量索引 */1 * * * * /bin/sh /data/www/rendawushui/Data/cron/sphinx_index_update.sh
#/usr/local/sphinx/bin/indexer --config /usr/local/sphinx/etc/sphinx.conf --rotate delta_expsys >> /usr/local/sphinx/var/log/delta_expsys.log 

# 合并 
#/usr/local/sphinx/bin/indexer --config /usr/local/sphinx/etc/sphinx.conf --merge expsys delta_expsys --rotate >> /usr/local/sphinx/var/log/expsys_merge_index.log 
