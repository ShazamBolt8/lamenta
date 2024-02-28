<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
require_once("$server_root/libs/Parsedown.php");
$parser = new Parsedown();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    About Us |
    <?php echo $site_name; ?>
  </title>
  <?php require_once("$server_root/components/metadata.php"); ?>
  <link rel="stylesheet" href="/information/information.css" />
  <link rel="stylesheet" href="/styles/hljs.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
  <script>
    hljs.highlightAll();
  </script>
</head>

<body>
  <div class="container">
    <?php require_once("$server_root/components/navbar.php"); ?>
    <div class="box aim">
      <img src="<?php echo $site_banner; ?>" alt="<?php echo $site_name; ?>">
      <p>Welcome to
        <?php echo $site_name; ?>, a digital platform dedicated to the relentless pursuit of knowledge and the
        dissemination
        of
        authentic information. Inspired by the spirit of Wikipedia,
        <?php echo $site_name; ?> is more than just a website; it's a
        global
        community driven by a shared commitment to truth, accuracy, and the free exchange of ideas.
      </p>
      <h1><span class="material-symbols-rounded">flare</span>Our Mission</h1>

      <p>At
        <?php echo $site_name; ?>, our mission is clear: to make authentic information globally accessible to everyone.
        In a world
        where misinformation can spread like wildfire, we stand as a beacon of reliability. We believe that knowledge is
        a
        powerful force for positive change, and we're here to harness that power.
      </p>

      <h1><span class="material-symbols-rounded">award_star</span>The
        <?php echo $site_name; ?> Experience
      </h1>

      <p>When you explore
        <?php echo $site_name; ?>, you'll discover a treasure trove of meticulously curated articles, resources, and
        insights spanning a wide range of topics. Whether you're seeking in-depth explanations of complex subjects,
        looking to expand your horizons, or simply hoping to satisfy your curiosity, you'll find it all here.
      </p>

      <h1><span class="material-symbols-rounded">local_library</span>Community-Driven Collaboration</h1>

      <p>
        <?php echo $site_name; ?> is more than just a website; it's a thriving community of knowledge enthusiasts,
        experts, and curious
        minds from around the globe. We believe in the power of collaborative learning and invite you to join us in
        creating, editing, and enhancing the content that makes
        <?php echo $site_name; ?> a valuable resource for all.
      </p>

      <h1><span class="material-symbols-rounded">bolt</span>Empowering Individuals and Societies</h1>

      <p>We believe that access to accurate information is not just a privilege but a fundamental right.
        <?php echo $site_name; ?> is
        committed to empowering individuals and societies by providing the tools and knowledge needed to make informed
        decisions, spark creativity, and drive positive change.
      </p>

      <h1><span class="material-symbols-rounded">explore</span>Explore. Learn. Share.</h1>

      <p>Whether you're a student, a professional, or simply someone with a thirst for knowledge,
        <?php echo $site_name; ?> welcomes you
        to
        explore our ever-expanding library of articles, engage with our passionate community, and contribute your
        expertise to the greater good.
      </p>
      <br>
      <p>Join us on a journey where knowledge meets authenticity. Together, we can build a brighter and more informed
        future.</p><br><br>
      <p>
        <?php echo $site_name; ?> Policy is available <a href="/policy/">here</a>. Feel free to contact us at
        <?php echo "<a href=\"$site_email\">$site_email</a>"; ?>
      </p>

    </div>
    <div class="box rules">
      <h1 class="big"><span class="material-symbols-rounded">shield_person</span>For Authors:</h1>
      <ul>
        <li><b>Original Content:</b> Authors should create original content and avoid using AI-generated or automated
          content.</li>
        <li><b>No Misinformation:</b> Ensuring the accuracy of information is paramount. Authors must not spread
          misinformation.</li>
        <li><b>No Plagiarism:</b> Plagiarism is strictly prohibited. Authors should give proper credit when using
          someone else's work.</li>
        <li><b>No Xenophobia or Racism:</b> Authors must refrain from promoting xenophobia or racism in their content.
        </li>
        <li><b>Freedom of Speech with Responsibility:</b> While freedom of speech is encouraged, authors should express
          their views responsibly and provide factual support for their claims.</li>
        <li><b>Quality Content:</b> Authors should strive to create high-quality content that benefits readers.</li>
        <li><b>No Harassment or Hate Speech:</b> Harassment, hate speech, or any form of harmful content is not allowed.
        </li>
        <li><b>XSS and Exploits Prevention:</b> We've empowered authors to employ Javascript and HTML in their articles
          for creative purposes. However, any effort to engage in XSS will be addressed appropriately.</li>
      </ul>
      <h1 class="big"><span class="material-symbols-rounded">groups</span>For Visitors:</h1>
      <ul>
        <li><b>No Spam:</b>Users should refrain from posting spam content or engaging in spammy behavior.</li>

        <li><b>No Abuse of Site:</b> Users must not abuse or exploit the platform in any way that disrupts its normal
          functioning.</li>
      </ul>
      <p>
        <b>Enforcement:</b> Non-compliance with these rules may result in a ban from the site. Enforcement should be
        fair and
        consistent, focusing on maintaining a positive and reliable environment for all users.
      </p>
    </div>
    <div class="box writing">
      <h1 class="big">Writing</h1>
      <?php
      $writingGuide =
        "$site_name uses Markdown.

---

1. ## Headings

Headings of varying sizes can be added to your article as follows:

```markdown
# Example
## Example
### Example
```

This will render as:

---
# Example
## Example
### Example
---

2. ## Images
You can insert images using the following syntax:

```markdown
![Optional Description](URL)
```

For example:

```
![Cute Cat with sunglasses](https://i.ibb.co/3Sqb8Jn/9cb05a8d56ae.jpg)
![](https://i.ibb.co/3Sqb8Jn/9cb05a8d56ae.jpg)
```

3. ## Links
You can include links directly in Markdown. For instance, a URL can be added like this: https://svgrepo.com/. If you prefer to display a link with custom text, you can format it as follows:

```markdown
[TEXT](URL)
```

For instance:

```markdown
[Visit SVG Repo](https://svgrepo.com/)
```

will render as [Visit SVG Repo](https://svgrepo.com/)

4. ## Lists
Markdown provides multiple ways to create lists, depending on your preference. Here are some common methods:

### Unordered Lists (Bullet Points)
You can create unordered lists using asterisks (*), plus signs (+), or hyphens (-), followed by a space:

```markdown
* Item 1
+ Item 2
- Item 3
```

### Ordered Lists (Numbered Lists)
To create ordered lists, use numbers followed by a period and a space:

```markdown
1. First item
2. Second item
3. Third item
```

### Nested Lists
You can nest lists by adding extra spaces before list items:

```markdown
* Main item 1
    * Sub-item A
    * Sub-item B
* Main item 2
    * Sub-item C
```

5. ## Quotes
To create blockquotes, use the greater-than symbol followed by a space:

```markdown
> This is a blockquote.
```

6. ## Code Blocks
To include code blocks, wrap your code in triple backticks like \`\`\`this\`\`\`. You can also specify the language for syntax highlighting:

```py
def hello_world():
    print(\"Here I have escaped them, you must not escape the backticks using \ \")
```



## Inline Code
For inline code, wrap the code in single backticks:

```markdown
Here is some `inline code`.
```

7. ## Tables
You can create tables using pipes *|* and hyphens *-* to separate columns and headers:

```markdown
| Header 1 | Header 2 |
|----------|----------|
| Row 1    | Row 1    |
| Row 2    | Row 2    |
```";
      echo $parser->parse($writingGuide);
      ?>
    </div>
  </div>
</body>

</html>