<?php

class SqlFormatter
{
    public function head()
    {
        ?>
        <script<?php echo nonce(); ?> src="/js/sql-formatter.min.js"></script>
        <script<?php echo nonce(); ?>>
            function insertAfter(referenceNode, newNode) {
                referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
            }
            addEventListener('DOMContentLoaded', () => {
                const codeEditor = document.querySelector('.sqlarea.jush-sql.jush');
                if (codeEditor) {
                    const sqlTextarea = document.querySelector('textarea[name=query]');
                    const btnPretty = document.createElement('a');
                    btnPretty.type = 'button';
                    btnPretty.style.cursor = 'pointer';
                    btnPretty.style.userSelect = 'none';
                    btnPretty.innerHTML = 'Авто-формат';
                    btnPretty.onclick = () => {
                        const formattedSql = window.sqlFormatter.format(
                            sqlTextarea.value
                        );
                        codeEditor.innerHTML = jush.highlight(
                            'sql',
                            formattedSql
                        );
                        sqlTextarea.value = formattedSql;
                    }
                    insertAfter(
                        codeEditor,
                        btnPretty
                    )
                }
            });
        </script>
        <?php
    }
}
