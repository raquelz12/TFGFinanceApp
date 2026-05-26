import pytest
from selenium.webdriver.support.ui import WebDriverWait
from pages.login_page import LoginPage
from pages.objective_page import ObjectivesPage

@pytest.fixture
def logged_user(driver):
    login = LoginPage(driver)
    login.open()
    login.login("test@test.test", "Test1234")
    
    wait = WebDriverWait(driver, 10)
    wait.until(lambda d: "login" not in d.current_url)
    return driver

def test_open_objectives_page(logged_user):
    page = ObjectivesPage(logged_user)
    page.open()

    assert "objectives" in logged_user.current_url, "No se ha podido acceder a la página de objetivos"

def test_add_objective(logged_user):
    page = ObjectivesPage(logged_user)
    page.open()
    
    name = "Viaje a Japón"
    
    if page.objective_exists(name):
        page.delete_objective(name)

    page.add_objective(name=name, target="2000", initial="500", date_str="12122026")

    assert page.objective_exists(name), "No se ha podido añadir el objetivo"

    current, target = page.get_objective_progress(name)
    assert current == "500.00", f"El valor actual del objetivo no es correcto, se esperaba 500.00 pero se obtuvo {current}"
    assert target == "2000.00", f"El valor objetivo del objetivo no es correcto, se esperaba 2000.00 pero se obtuvo {target}"

def test_add_money_objective(logged_user):
    page = ObjectivesPage(logged_user)
    page.open()
    
    name = "Viaje a Japón"
    
    if not page.objective_exists(name):
        page.add_objective(name=name, target="2000", initial="500", date_str="12122026")

    page.add_money_to_objective(name, "200")

    current, target = page.get_objective_progress(name)
    assert current == "700.00", f"El valor actual del objetivo no es correcto, se esperaba 700.00 pero se obtuvo {current}"
    assert target == "2000.00", f"El valor objetivo del objetivo no es correcto, se esperaba 2000.00 pero se obtuvo {target}"

def test_edit_objective(logged_user):
    page = ObjectivesPage(logged_user)
    page.open()
    
    name = "Viaje a Japón"
    
    if not page.objective_exists(name):
        page.add_objective(name=name, target="2000", initial="500", date_str="12122026")

    page.edit_objective_target(name, "3000")

    current, target = page.get_objective_progress(name)
    assert target == "3000.00", f"El valor objetivo del objetivo no es correcto, se esperaba 3000.00 pero se obtuvo {target}"

def test_delete_objective(logged_user):
    page = ObjectivesPage(logged_user)
    page.open()
    
    name = "PC"
    
    if not page.objective_exists(name):
        page.add_objective(name=name, target="1000", initial="100", date_str="31122025")

    page.delete_objective(name)

    assert not page.objective_exists(name), f"No se ha podido eliminar el objetivo"