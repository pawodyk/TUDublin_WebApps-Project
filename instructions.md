<hr>

# BN026 Computing - Web Applications

<hr>


## Individual Project – semester 2 – 2019-20

DEADLINE: Moodle URL upload by 4pm Tues 19 th May 2020

You are to design, develop, test, publish and defend a database-driven PHP website, to illustrate the topics of the module. Your project should be an original approach to developing a website based on the case study (see next page).


NOTE:

- Projects submitted on a topic different will score zero (unless a different case study has been agreed and confirmed by email with the module lecturer – before week 7)

- Each student is strongly advised to create a high-level design for their own approach to the general case study topic

    - For formative feedback from lecture bring to the labs printed Use Case and ER-diagrams outlining your proposed web application – the more detail in this high-level design the easier it is to create the website …

Submit the following no later than the deadline at the top of this page:

1. **URL of private Github project source code repository**
    
    NOTE: you must add **dr-matt-smith** as a collaborator to your private repository

2. **URL of published website**   
    this must be up and working from the submission deadline until the end of May The published website should exactly match the contents of your final Github commit
    
3. **Project Defence Document (PDF or MP4 screencast)**
    you may additionally submit one document to argue how your project meets the requirements and marking criteria (**EITHER** a PDF file **OR** an MP4 screencast) PDF (max 4 pages) / Screencast video (max 5 minutes)

NOTE: Please either have migration code (e.g. db/migrateAndLoadFixtures.php) to create your database tables and initial data, or provide a single error-free SQL text file, with your create-table and insert statements to setup the database for the project to work

- You must include setup data for at least the following:
    - User table:
        - Top level administrator user, username/password = admin / admin
        - Basic user, username/password = user/user
        - Special role for your case study user – choose a simple username/password for this test user
- For a good grade
    - also have fixtures data for enough tables for a good demonstration of your website without users having to do lots of work through your forms
    - demonstrate good use of Faker

<hr>

## Project Case Study 2020 

Fictional company: **CSR Limited - CoffeeShopReview.ie**

- company running an online review of coffee shops around Ireland
- coffee shop owners can pay to be able to create their own profile page

***NOTE: If you don’t like coffee shops, you can personalise this shop review website project for some other kind of pub / restaurant / hotel / etc.***

**Members of the public** (no login needed) can:
- see summary list of coffee shops
- for each shop viewers can:
    - view the coffee shop summary
        - if shop owner is a paid site member, this will also include the shop owners profile content at the bottom of the page
    - see list of accepted customer comments (most recent first)
    - the reviews for the shop
- submit customer comments about coffee shops
    - but they don’t go public until approved by a staff reviewer


**CSR Staff (ROLE_STAFF)** can:
- add a new coffee shop & write the shop summary
- add a review for a shop
    - so one coffee shop can have several reviews (listed by date)
    - each review should offer a star rating, and the shop should have an average star rating based on all its reviews
- look at customer submitted comments for a coffee shop, and either reject them (they get deleted), or accept them (the customer comments get published) 


**Coffee shop owners (ROLE_SHOP)** can:
- CRUD the owners shop profile content
- (note – one owner may own more than one shop …)


**Site administrators (ROLE_ADMIN)** can:
- CRUD all kinds of user account
- Associate a shop owner user with one or more coffee shops

<hr>

## WEBSITE PROJECT REQUIREMENTS

### A set of public pages (no login required)
- a non-logged-in site visitor can view general information pages (e.g. index / about / sitemap)
- site visitor can see a list of public items or people (or categories then items per category): TABLE-per-page
- site visitor can select a public item or person and see its details on a single page: RECORD-per-page
- plus:
    - your public Use Cases for this system
- note – please do not add a user self-registration feature – just setup users in your DB fixtures

### A set of database CRUD pages (“user” login required) ROLE_STAFF
- user has same access as public user
- plus
    - for at least one database table containing users can CRUD:
        - Update/Edit item details
        - Delete items
        - Create new items
- plus:
    - the Use Cases for this system

### A set of database CRUD pages (“admin” login required) ROLE_ADMIN
- admin user has same access as staff user
- plus
    - admin users can also CRUD user records (for staff and admin users)
- plus:
    - your admin Use Cases for this system

### Meta-criteria for programming / web development projects (keep these in mind all the time)
- Originality (your own work)
- Correctness (it works)
- Completeness (features specified are present)
- Code Quality & Software Engineering Process
    - good quality and meaningful identifiers (names) files / folders / classes / ID’s
    - decent and consistent indentation and code layout – the PHP PSR standards
    - illustrate MVC and Front Controller architecture & directory structure
    - as taught in the module (so do NOT use a different software architecture)
    - use of tools (Composer, phpDocumentor, Github, PhpUnit)
- Usability – a decent user experience
- Consistency & Coherence – decent website ‘look and feel’ (please avoid drop-down nav-bars)
    - a simple but effective website will score better than a hard to understand / messy complicated website – so keep a non-technical website visitor in mind at all times …
- Technical Challenge – Demonstration of attempting interesting / challenging features
    - note – there is no extra credit for using JavaScript … so don’t make work for yourself
- Value Added (more than just examples reproduced)
- less is more … (Matt hates drop-down navbars – no click should be needed to see relevant links…)
    - a simple but effective website will score better than a hard to understand / messy complicated website – so keep a non-technical website visitor in mind at all times …

<hr>

## Academic Honesty

By submitting your project for assessment you agree to the following:

“The material contained in this assignment is my own original work, except where work is clearly identified and duly acknowledged. No aspect of this assignment has been previously submitted for assessment in any other unit or course.”

In general: For every piece of work you submit to the Institute, your documentation must make it very clear which parts are your own creations; the work of others; and your adaptation of other’s work, and what your adaptations were. Work submitted without full and unambiguous acknowledgements is plagiarism. Plagiarism and academic dishonesty can lead to failure of the module and other penalties outlined in the Institute’s rules and regulations. For any project or coursework, you should discuss how to best declare the use of work from other sources with your lecturers.

This assignment is an individual project. The work you submit must be your own (with fully declared exceptions described below). It is fine for you to ask a lecturer or fellow student for assistance with some problem you are stuck on during the project, but the actual final work created and submitted must be your own. While you may get IDEAS for code to solve particular problems from other sources, you must write and user the command line tools to generate all codes yourself. Remember by submitting this project you are declaring that it is all your own individual work unless explicitly and unambiguously acknowledged by you.

You must NOT SHARE YOUR OWN CODE FILES with fellow students! Also ensure your project Github repository is private!

You should NOT BE TYPING IN CODE into a fellow student’s computer project – talk to them yes, but let THEM design and type-in their own code.

You may NOT use other PHP code inserted into your own classes or scripts - i.e. all your PHP classes must all be your own code, or generated by you at the command line, or imported through the Composer package manager utility.

The text content of your web pages should all be prose text that YOU have authored yourself (so do NOT copy and paste from Wikipedia or IMDB etc.) – pages only need a few sentences/paragraphs each, so write and paraphrase them yourself in your own words. Or you may generate random content, e.g. with Faker.

You may use the following without need of citation:
    (1) you may use and modify any example codes from the lectures/labs on Moodle, also any of the
lecturer’s GitHub sources
(2) you may (and in fact are encouraged to) use full PHP components which you install using
Composer. These are therefore stored in the /vendor directory, and your composer.json file
lists all project dependencies for third-party components
You may use the following, with citation in a /public/sources.txt document
(3) client-side media files, such as images, fonts, and templates for HTML/CSS/JavaScript
• you must declare these in your /public/sources.txt document
- give the URL and list what client-side files you used from that location
Talk to the lecturer if you have any doubt about whether it is permitted to use / how to properly cite
resources you have not created yourself for this module project.
webApplicationsProject.docx Page 5 of 5

<hr>

## Declaration of media sources

`\public\sources.txt – text file declaring sources`

- You are to submit a text file which states the origin of each image (and font) used in your website (be specific, e.g. saying “Google images” is not good enough! You need to state the URL including the original filename of the image from that source, and also state the image filename as it appears in your website folder)
- If you created an image yourself, say so …
- You should also declare any other sources used in your website, and state clearly how they have been used, so it is clear which parts of your project are your own work, which are the work of others, and the extent to which you have changed any work from others
    - But remember, apart from images all other parts of the project should be your own original work …
- Example of content:
    - File: \public\sources.txt
        ```
        logo.gif
        http://www.itb.ie/images/itb_logo.gif
        
        cat.jpg
        http://images.wisegeek.com/young-calico-cat.jpg
        ```
    - And so on
    - All you need is to state the name of the image file in your images folder, and the URL of where I can find the original image on the web image
    - HINT: View Source will give you direct links to images that you see on a web page
        - Click that link and you should see the image, and be able to copy and paste the URL into your sources.txt document
- NOTE
    - Do NOT submit a PDF or Word document !!
        - just a TEXT FILE with simple contents as shown above
    - when you download an image / CSS file, that is a good time to rename it as you save it into your \web\images directory
        - you’ll be marked down for poorly named images like: p041gljk.jpg from: 
        http://ichef.bbci.co.uk/wwfeatures/wm/live/1280_640/images/live/p0/41/gl/p0
        41gljk.jpg
        - a much better file name would be: cat_face_closeup.jpg from:
        http://ichef.bbci.co.uk/wwfeatures/wm/live/1280_640/images/live/p0/41/gl/p0
        41gljk.jpg