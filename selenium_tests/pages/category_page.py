from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC

class CategoryPage:
    URL = "http://localhost/TFGFinanceApp/app/categories.php"

    OPEN_ADD_MODAL = (By.ID, "openAddCategoryModal")
    OPEN_EDIT_MODAL = (By.CLASS_NAME, "edit-category-button")
    OPEN_DELETE_MODAL = (By.CLASS_NAME, "delete-category-button")
    SELECT_CATEGORY = (By.ID, "categorySelect")
    AMOUNT_INPUT = (By.ID, "categoryAmount")
    SUBMIT = (By.ID, "confirmAddCategoryButton")
    MESSAGE = (By.CLASS_NAME, "message")

    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)

    def open(self):
        self.driver.get(self.URL)

    def add_category(self, option=1, amount="100"):
        self.wait.until(
            EC.element_to_be_clickable(self.OPEN_ADD_MODAL)
        ).click()
        select_element = self.wait.until(
            EC.visibility_of_element_located(self.SELECT_CATEGORY)
        )
        Select(select_element).select_by_index(option)
        self.wait.until(
            EC.visibility_of_element_located(self.AMOUNT_INPUT)
        ).send_keys(amount)
        self.wait.until(
            EC.element_to_be_clickable(self.SUBMIT)
        ).click()
        self.wait.until(EC.visibility_of_element_located(self.MESSAGE))

    def edit_category(self, category_name, new_amount="150"):
        edit_button = self.wait.until(
            EC.element_to_be_clickable(
                (By.XPATH, f"(//button[contains(@class, 'edit-category-button') and @data-category='{category_name}'])[1]")
            )
        )
        edit_button.click()
        self.wait.until(
            EC.visibility_of_element_located(self.AMOUNT_INPUT)
        ).clear()
        self.wait.until(
            EC.visibility_of_element_located(self.AMOUNT_INPUT)
        ).send_keys(new_amount)
        self.wait.until(
            EC.element_to_be_clickable(self.SUBMIT)
        ).click()
        self.wait.until(
            EC.visibility_of_element_located(self.MESSAGE)
        )
    
    def delete_category(self, category_name):
        delete_button = self.wait.until(
            EC.element_to_be_clickable(
                (By.XPATH, f"(//button[contains(@class, 'delete-category-button') and @data-category='{category_name}'])[1]")
            )
        )
        delete_button.click()
        self.wait.until(
            EC.element_to_be_clickable((By.CLASS_NAME, "confirm-delete-category"))
        ).click()
        self.wait.until(
            EC.visibility_of_element_located(self.MESSAGE)
        )

    def category_exists(self, category_name):
        try:
            self.wait.until(
                EC.visibility_of_element_located(
                    (By.XPATH, f"//div[@data-category-name='{category_name}']")
                )
            )
            return True
        except:
            return False
    
    def get_category_amount(self, category_name):
        category_card = self.driver.find_element(
            By.XPATH, f"//div[@data-category-name='{category_name}']"
        )
        amount_element = category_card.find_element(By.CLASS_NAME, "category-amount").text
        budget_part = amount_element.split("/")[1].strip()
        budget_value = budget_part.replace("€", "").strip()

        return budget_value
    
    def is_category_over_budget(self, category_name):
        category_card = self.driver.find_element(
            By.XPATH, f"//div[@data-category-name='{category_name}']"
        )
        return "over-budget" in category_card.get_attribute("class")