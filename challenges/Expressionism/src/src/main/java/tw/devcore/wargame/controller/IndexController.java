package tw.devcore.wargame;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RequestMethod;

import java.util.Random;
import javax.servlet.http.HttpSession;

@Controller
public class IndexController {

    private final Random random = new Random();

    @RequestMapping(value="/", method=RequestMethod.GET)
    public String index(@RequestParam(value="id", required=false) String id, Model model, HttpSession session) {
        if (id == null) {
            id = String.valueOf(random.nextInt(11) + 1);
        }
        model.addAttribute("id", id);
        session.setAttribute("FLAG", System.getenv("FLAG"));
        return "index";
    }
}
