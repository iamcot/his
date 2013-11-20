{{* Frame template of medocs page *}}
{{* Note: this template uses a template from the /registration_admission/ *}}

<table width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td>{{include file="medocs/tabs.tpl"}}</td>
        </tr>

        <tr>
            <td>
                <table width="700" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td>
                                    {{include file="registration_admission/basic_data_khac.tpl"}}
                            </td>
                        </tr>
                        <tr></tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <br/>
                {{$sListLinkIcon}} {{$sListRecLink}}<br/><br/>
                {{$sListLinkIcon}} {{$sListRecLink1}}<br/><br/>
                {{$pbBottomClose}}
            </td>
        </tr>
    </tbody>
</table>
