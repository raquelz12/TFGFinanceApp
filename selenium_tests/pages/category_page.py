import re
import time
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC

class CategoryPage:
    URL = "http://host.docker.internal/TFGFinanceApp/app/categories.php"

    OPEN_ADD_MODAL = (By.ID, "openAddCategoryModal")
    MESSAGE = (By.CLASS_NAME, "message")
    
    ADD_SELECT_CATEGORY = (By.ID, "categorySelect")
    ADD_AMOUNT_INPUT = (By.ID, "categoryAmount")
    ADD_SUBMIT = (By.ID, "confirmAddCategoryButton")

    EDIT_AMOUNT_INPUT_ACTIVE = (By.CSS_SELECTOR, ".modal.show .edit-amount-input")
    EDIT_SUBMIT_ACTIVE = (By.CSS_SELECTOR, ".modal.show .confirm-edit-category")
    DELETE_CONFIRM_ACTIVE = (By.CSS_SELECTOR, ".modal.show .confirm-delete-category")

    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)

    def open(self):
        self.driver.get(self.URL)
        self.wait.until(lambda d: d.execute_script("return document.readyState") == "complete")

    def add_category(self, category_text="Alimentación", amount="100"):
        self.wait.until(EC.element_to_be_clickable(self.OPEN_ADD_MODAL)).click()
        
        select_element = self.wait.until(EC.visibility_of_element_located(self.ADD_SELECT_CATEGORY))
        Select(select_element).select_by_visible_text(category_text)
        
        self.wait.until(EC.visibility_of_element_located(self.ADD_AMOUNT_INPUT)).send_keys(amount)
        
        self.wait.until(EC.element_to_be_clickable(self.ADD_SUBMIT)).click()
        
        self.wait.until(EC.visibility_of_element_located(self.MESSAGE))
        time.sleep(0.5)

    def edit_category(self, category_name, new_amount="150"):
        xpath_edit_btn = f"(//button[contains(@class, 'edit-category-button') and @data-category-name='{category_name}'])[1]"
        edit_button = self.wait.until(EC.element_to_be_clickable((By.XPATH, xpath_edit_btn)))
        edit_button.click()

        edit_input = self.wait.until(EC.visibility_of_element_located(self.EDIT_AMOUNT_INPUT_ACTIVE))
        edit_input.clear()
        edit_input.send_keys(new_amount)

        save_btn = self.wait.until(EC.element_to_be_clickable(self.EDIT_SUBMIT_ACTIVE))
        save_btn.click()

        self.wait.until(EC.visibility_of_element_located(self.MESSAGE))
        time.sleep(0.5)
    
    def delete_category(self, category_name):
        xpath_delete_btn = f"(//button[contains(@class, 'delete-category-button') and @data-category-name='{category_name}'])[1]"
        delete_button = self.wait.until(EC.element_to_be_clickable((By.XPATH, xpath_delete_btn)))
        delete_button.click()
        
        confirm_btn = self.wait.until(EC.element_to_be_clickable(self.DELETE_CONFIRM_ACTIVE))
        confirm_btn.click()
        
        self.wait.until(EC.visibility_of_element_located(self.MESSAGE))
        time.sleep(0.5)

    def category_exists(self, category_name):
        try:
            WebDriverWait(self.driver, 2).until(
                EC.presence_of_element_located((By.XPATH, f"//div[@data-category-name='{category_name}']"))
            )
            return True
        except:
            return False
    
    def get_category_amount(self, category_name):
        category_card = self.driver.find_element(By.XPATH, f"//div[@data-category-name='{category_name}']")

        try:
            amount_element = category_card.find_element(By.CLASS_NAME, "category-amount").text
            budget_part = amount_element.split("/")[1].strip()
            return budget_part.replace("€", "").strip()
        except:
            over_budget_element = category_card.find_element(By.CLASS_NAME, "over-budget").text
            match = re.search(r"([\d\.]+)", over_budget_element)
            if match:
                return match.group(1)
            return "0"
    
    def is_category_over_budget(self, category_name):
        category_card = self.driver.find_element(By.XPATH, f"//div[@data-category-name='{category_name}']")
        elements = category_card.find_elements(By.CLASS_NAME, "over-budget")
        return len(elements) > 0