package tw.devcore.wargame;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.web.servlet.support.SpringBootServletInitializer;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.server.ResponseStatusException;

import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import org.springframework.http.HttpStatus;

@SpringBootApplication
@Controller
public class Application extends SpringBootServletInitializer{

    @PersistenceContext
    private EntityManager entityManager;

    public static void main(String[] args) {
        SpringApplication.run(Application.class, args);
    }

    @GetMapping("/{route}")
    public String index(@PathVariable String route, Model model) {
        return entityManager.createQuery(
                String.format("FROM Pages WHERE route = '%s'", route), Pages.class)
                .getResultStream()
                .findFirst()
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Page not found"))
                .name;
    }
}
