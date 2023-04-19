Before run steps of installation, you need to make sure that have been docker installed in your local PC/Laptop

1 Clone project from master branch
```
https://github.com/satryawiguna/cognotiv-blog.git
```

2 Open terminal then go inside to root project folder 

3 Type and run following command
```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

4 Type and run following command
```
sail up -d
```

5 Type and run following command
```
sail artisan serve
```

6 Goes to inside "vue" folder then run the command as following
```
npm install
```

7 Once it done, type and run the following command
```
npm run dev
```

I also attached the export insomnia file for api endpoint reference
