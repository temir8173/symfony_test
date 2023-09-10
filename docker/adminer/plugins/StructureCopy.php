<?php

/**
 * Позволяет копировать структуру для разных языков программирования
 */
class StructureCopy
{
    /**
     * @param $fields
     * @return void
     */
    function tableStructurePrint($fields) {
        ?>
        <div style="margin: 10px 0;">
            Копировать для:<br/>
            PHP (<a href="#" id="copy-php-array">array</a>
            | <a href="#" id="copy-php-array-assoc">array_assoc</a>
            | <a href="#" id="copy-php-class">class</a>
            | <a href="#" id="copy-php-constructor">constructor</a>
            | <a href="#" id="copy-php-function-params">function_params</a>)<br/>
            JS (<a href="#" id="copy-js-array">array</a> | <a href="#" id="copy-js-json">json</a>)
            <div id="copy-structure-status" style="display: none;">
                Скопировано в буфер <input id="inp-close-copy-structure-textarea" type="button" value="Закрыть"/>
            </div>
            <textarea
                id="copy-structure-textarea"
                style="display: none; width: 700px; height: 500px; margin-top: 5px;"></textarea>
        </div>
        <script<?= nonce() ?>>
            (function () {
                const copyStructureTextarea = document.getElementById('copy-structure-textarea');
                const copyStructureStatus = document.getElementById('copy-structure-status');
                const inpCloseCopyStructureTextarea = document.getElementById('inp-close-copy-structure-textarea');
                const fields = <?= json_encode($fields) ?>;
                const snakeCaseToCamelCase = str =>
                    str.toLowerCase().replace(/([-_][a-z])/g, group =>
                        group
                            .toUpperCase()
                            .replace('-', '')
                            .replace('_', '')
                    );
                const setTextareaText = text => {
                    copyStructureStatus.style.display = 'block';
                    copyStructureTextarea.style.display = 'block';
                    copyStructureTextarea.value = text;
                    navigator.clipboard.writeText(text);
                }
                const copyPhpArray = () => {
                    let code = '[\n';
                    for (const fieldName in fields) {
                        if (!fields.hasOwnProperty(fieldName)) {
                            continue;
                        }
                        code += `\t'${fieldName}',\n`;
                    }
                    code += ']';
                    setTextareaText(
                        code
                    );
                }
                const copyPhpArrayAssoc = () => {
                    let code = '[\n';
                    for (const fieldName in fields) {
                        if (!fields.hasOwnProperty(fieldName)) {
                            continue;
                        }
                        const field = fields[fieldName];
                        const value = field.null ? 'null' : 'value';
                        code += `\t'${fieldName}' => ${value},\n`;
                    }
                    code += ']';
                    setTextareaText(
                        code
                    );
                }
                const phpTypeAliases = {
                    'tinyint': 'int',
                    'varchar': 'string',
                    'char': 'string',
                    'text': 'string',
                    'datetime': 'DateTime',
                    'date': 'DateTime',
                };
                const getPhpTypeByField = field => {
                    let type;
                    if (field.full_type === 'tinyint(1)') {
                        type = 'bool';
                    } else {
                        type = phpTypeAliases[field.type] || field.type;
                    }
                    return (field.null ? '?' : '') + type;
                }
                const copyPhpClass = () => {
                    let code = '';
                    for (const fieldName in fields) {
                        if (!fields.hasOwnProperty(fieldName)) {
                            continue;
                        }
                        const field = fields[fieldName];
                        const type = getPhpTypeByField(field);
                        code += '/**\n'
                            + (field.comment ? ` * ${field.comment}\n` : '')
                            + ` * @var ${type}\n`
                            + ' */\n'
                            + `private ${type} $${snakeCaseToCamelCase(fieldName)}`
                            + (field.null ? ' = null' : '')
                            + ';\n\n';
                    }
                    setTextareaText(
                        code
                    );
                }
                const copyPhpConstructor = () => {
                    let code = '/**\n';
                    let paramsCode = '';
                    for (const fieldName in fields) {
                        if (!fields.hasOwnProperty(fieldName)) {
                            continue;
                        }
                        const field = fields[fieldName];
                        const type = getPhpTypeByField(field);
                        const varName = '$' + snakeCaseToCamelCase(fieldName);
                        code += ` * @param ${type} ${varName}`
                            + (field.comment ? ' ' + field.comment : '')
                            + '\n';
                        paramsCode += `\tprivate readonly ${type} ${varName}` + (field.null ? ' = null' : '') + ',\n';
                    }
                    code += ' */\n'
                    + 'public function __construct(\n'
                    + paramsCode
                    + ') {\n}';
                    setTextareaText(
                        code
                    );
                }
                const copyPhpFunctionParams = () => {
                    let rows = [];
                    for (const fieldName in fields) {
                        if (!fields.hasOwnProperty(fieldName)) {
                            continue;
                        }
                        const snakeCaseFieldName = snakeCaseToCamelCase(fieldName);
                        rows.push(`${snakeCaseFieldName}: $row['${snakeCaseFieldName}'],`);
                    }
                    setTextareaText(
                        rows.join('\n')
                    );
                }
                const copyJsArray = () => {
                    let code = '{\n';
                    for (const fieldName in fields) {
                        if (!fields.hasOwnProperty(fieldName)) {
                            continue;
                        }
                        const field = fields[fieldName];
                        const value = field.null ? 'null' : 'value';
                        code += `\t${fieldName}: ${value},\n`;
                    }
                    code += '}';
                    setTextareaText(
                        code
                    );
                }
                const copyJsJson = () => {
                    let code = '{\n';
                    for (const fieldName in fields) {
                        if (!fields.hasOwnProperty(fieldName)) {
                            continue;
                        }
                        const field = fields[fieldName];
                        const value = field.null ? 'null' : 'value';
                        code += `\t"${fieldName}": ${value},\n`;
                    }
                    code += '}';
                    setTextareaText(
                        code
                    );
                }
                document.getElementById('copy-php-array').addEventListener('click', copyPhpArray);
                document.getElementById('copy-php-array-assoc').addEventListener('click', copyPhpArrayAssoc);
                document.getElementById('copy-php-class').addEventListener('click', copyPhpClass);
                document.getElementById('copy-php-constructor').addEventListener('click', copyPhpConstructor);
                document.getElementById('copy-php-function-params').addEventListener('click', copyPhpFunctionParams);
                document.getElementById('copy-js-array').addEventListener('click', copyJsArray);
                document.getElementById('copy-js-json').addEventListener('click', copyJsJson);
                inpCloseCopyStructureTextarea.addEventListener('click', () => {
                    copyStructureStatus.style.display = 'none';
                    copyStructureTextarea.style.display = 'none';
                });
            })()
        </script>
        <?php
    }
}

return new StructureCopy();
