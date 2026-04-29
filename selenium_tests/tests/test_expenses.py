import pytest
from selenium.webdriver.support.ui import WebDriverWait
from pages.login_page import LoginPage
from pages.expense_page import ExpensesPage
from pages.category_page import CategoryPage

@pytest.fixture
def logged_user(driver):
    login = LoginPage(driver)
    login.open()
    login.login("test@test.test", "Test1234")
    
    wait = WebDriverWait(driver, 10)
    wait.until(lambda d: "login" not in d.current_url)
    return driver

def category_exists(driver, cat_name="Alimentación"):
    cat_page = CategoryPage(driver)
    cat_page.open()
    return cat_page.category_exists(cat_name)

def test_open_expenses_page(logged_user):
    page = ExpensesPage(logged_user)
    page.open()
    assert "expenses" in logged_user.current_url, f"No se pudo acceder a la página de gastos."

def test_add_expense(logged_user):
    driver = logged_user
    
    test_category = "Alimentación"

    if not category_exists(driver, test_category):
        cat_page = CategoryPage(driver)
        cat_page.open()
        cat_page.add_category(category_text=test_category, amount="100")

    page = ExpensesPage(driver)
    page.open()
    
    expense_name = "Test Añadir"
    amount = "50.50"
    
    if page.expense_exists(expense_name):
        page.delete_expense(expense_name)

    page.add_expense(expense_name, amount, test_category)

    assert page.expense_exists(expense_name), f"No se pudo añadir el gasto."
    assert float(page.get_expense_amount(expense_name)) == 50.50, f"La cantidad del gasto no coincide con el valor ingresado."

def test_edit_expense(logged_user):
    driver = logged_user
    test_category = "Alimentación"
    if not category_exists(driver, test_category):
        cat_page = CategoryPage(driver)
        cat_page.open()
        cat_page.add_category(category_text=test_category, amount="500")

    page = ExpensesPage(driver)
    page.open()

    expense_name = "Test Editar"
    new_expense_name = "Test Editado"

    if not page.expense_exists(expense_name):
        page.add_expense(expense_name, "10.00", test_category)

    page.edit_expense(expense_name, new_expense_name, "20.00")

    assert not page.expense_exists(expense_name), f"No se pudo editar el gasto, el nombre antiguo sigue existiendo."
    assert page.expense_exists(new_expense_name), f"No se pudo editar el gasto."
    assert float(page.get_expense_amount(new_expense_name)) == 20.00, f"La cantidad del gasto editado no es correcta."

def test_delete_expense(logged_user):
    driver = logged_user
    test_category = "Alimentación"
    if not category_exists(driver, test_category):
        cat_page = CategoryPage(driver)
        cat_page.open()
        cat_page.add_category(category_text=test_category, amount="500")

    page = ExpensesPage(driver)
    page.open()

    expense_name = "Test Borrar"

    if not page.expense_exists(expense_name):
        page.add_expense(expense_name, "5.00", test_category)

    page.delete_expense(expense_name)

    assert not page.expense_exists(expense_name), f"No se pudo borrar el gasto."