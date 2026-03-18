from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

class ProfilePage:
    URL = "http://localhost/TFGFinanceApp/app/profile.php"

    NAME_INPUT = (By.CSS_SELECTOR, ".profile-page #name")
    EMAIL_INPUT = (By.CSS_SELECTOR, ".profile-page #email")
    SAVE_PROFILE_BTN = (By.ID, "saveChangesButton")

    OPEN_PWD_MODAL_BTN = (By.CSS_SELECTOR, "button[data-bs-target='#ModalChangePassword']")
    CURRENT_PWD_INPUT = (By.CSS_SELECTOR, "#ModalChangePassword #current_password")
    NEW_PWD_INPUT = (By.CSS_SELECTOR, "#ModalChangePassword #new_password")
    CONFIRM_PWD_INPUT = (By.CSS_SELECTOR, "#ModalChangePassword #confirm_new_password")
    SAVE_PWD_BTN = (By.ID, "savePasswordChangesButton")

    LOGOUT_BTN = (By.XPATH, "//button[contains(text(), 'Cerrar sesión')]")

    MESSAGE = (By.CSS_SELECTOR, ".form-error.message")
    PROFILE_CARD = (By.CLASS_NAME, "profile-card")

    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)

    def open(self):
        self.driver.get(self.URL)
        self.wait.until(
            EC.visibility_of_element_located(self.PROFILE_CARD)
        )

    def get_name(self):
        name_input = self.wait.until(
            EC.presence_of_element_located(self.NAME_INPUT)
        )

        return name_input.get_attribute("value")

    def is_email_disabled(self):
        email_input = self.wait.until(
            EC.presence_of_element_located(self.EMAIL_INPUT)
        )
        is_disabled = email_input.get_attribute("disabled") is not None

        return is_disabled

    def update_name(self, new_name):
        name_input = self.wait.until(
            EC.element_to_be_clickable(self.NAME_INPUT)
        )
        name_input.clear()
        name_input.send_keys(new_name)
        
        self.driver.find_element(*self.SAVE_PROFILE_BTN).click()
        time.sleep(1)

    def attempt_password_change(self, current, new, confirm):
        self.wait.until(
            EC.element_to_be_clickable(self.OPEN_PWD_MODAL_BTN)
        ).click()
        
        current_input = self.wait.until(
            EC.visibility_of_element_located(self.CURRENT_PWD_INPUT)
        )
        current_input.clear()
        current_input.send_keys(current)
        
        self.driver.find_element(*self.NEW_PWD_INPUT).send_keys(new)
        self.driver.find_element(*self.CONFIRM_PWD_INPUT).send_keys(confirm)
        
        self.driver.find_element(*self.SAVE_PWD_BTN).click()
        time.sleep(1)

    def logout(self):
        self.wait.until(
            EC.element_to_be_clickable(self.LOGOUT_BTN)
        ).click()
        self.wait.until(lambda d: "login" in d.current_url)