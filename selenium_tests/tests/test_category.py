def test_add_category(category_page):

    category_name = "Viajes"

    if not category_page.category_exists(category_name):
        category_page.add_category_by_name(category_name, amount="150")

    assert category_page.category_exists(category_name)


def test_edit_category(category_page):

    category_name = "Ocio"

    if not category_page.category_exists(category_name):
        category_page.add_category_by_name(category_name, amount="100")

    old_budget = category_page.get_category_amount(category_name)

    category_page.edit_category(category_name, new_amount="300")

    new_budget = category_page.get_category_amount(category_name)

    assert new_budget == "300"
    assert new_budget != old_budget


def test_delete_category(category_page):

    category_name = "Tecnología"

    if not category_page.category_exists(category_name):
        category_page.add_category_by_name(category_name, amount="120")

    category_page.delete_category(category_name)

    assert not category_page.category_exists(category_name)