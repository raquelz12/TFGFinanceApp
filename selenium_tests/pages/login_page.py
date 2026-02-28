from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class LoginPage:
    URL = "http://localhost/TFGFinanceApp/app/login.php"

    EMAIL = (By.ID, "email")
    PASSWORD = (By.ID, "password")
    SUBMIT = (By.ID, "loginButton")
    ERROR = (By.CLASS_NAME, "form_error")

    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)

    def open(self):
        self.driver.get(self.URL)

    def login(self, email, password):
        self.wait.until(
            EC.visibility_of_element_located(self.EMAIL)
        ).send_keys(email)
        self.wait.until(
            EC.visibility_of_element_located(self.PASSWORD)
        ).send_keys(password)
        self.wait.until(
            EC.element_to_be_clickable(self.SUBMIT)
        ).click()