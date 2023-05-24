<div class="search">
    <form class="search__form" method="get" action="">
        <fieldset class="search__fieldset">
            <legend class="search__legend">
                검색
            </legend>

            <table class="search__table">
                <colgroup>
                    <col width="">
                    <col width="">
                    <col width="">
                    <col width="">
                </colgroup>
                <tr>
                    <th>
                        <label for="" class="form-label search__label">
                            아이디/이름
                        </label>
                    </th>
                    <td>
                        <select name="" class="form-element">
                            <option value="">아이디</option>
                            <option value="">이름</option>
                        </select>
                        <input type="text" name="" class="form-element">
                    </td>
                    <th>
                        <label for="" class="form-label search__label">
                            권한
                        </label>
                    </th>
                    <td>
                        <select name="" class="form-element">
                            <option value="">아이디</option>
                            <option value="">이름</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="" class="form-label search__label">
                            조회기간
                        </label>
                    </th>
                    <td colspan="3">
                        <div class="datepicker-range">
                            <input type="text" name="" class="form-element datepicker-range__date" placeholder="YYYY-MM-DD">
                            <span class="datepicker-range__line">~</span>
                            <input type="text" name="" class="form-element datepicker-range__date" placeholder="YYYY-MM-DD">
                        </div>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="search__submit">
            <button type="submit" class="button button--search search__submit-button">
                <span class="button__text">검색</span>
            </button>
        </div>
    </form>
</div>